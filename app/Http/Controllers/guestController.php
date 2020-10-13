<?php

namespace App\Http\Controllers;

use App\Jobs;
use App\methods;
use App\redshifts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use GuzzleHttp\Client;
use App\User;
use GuzzleHttp\Exception\RequestException;


class guestController extends Controller
{

    public function index()
    {
    	return view('guest');
    }

    public function store(Request $request)
    {

		//method selection check logic
		$methodCount = methods::count();
		$y = 0;
		for($i=1;$i<$methodCount+1;$i++){
			if($request->input('method_id_for_files'.$i)!= null){
				$methodRequests[$y] = $request->input('method_id_for_files'.$i);
				$y = $y+1;
			}
		}

		if($y == 0){
			return back()->withErrors("At least one method must be selected.");
		}

		//creating job entry in the database
		$userId = 1;
		$job = new Jobs();
		$job->job_name = "guest job name";
		$job->job_description = "guest job description";
		$job->user_id = $userId;
		$job->save();
		$lastJob = DB::table('jobs')->latest('job_id')->where('user_id','=', $userId)->first();
		$jobId = $lastJob->job_id;

		//creating data to send in http json request
    	$galaxy = array();
		$galaxy[0] = new redshifts();
		$galaxy[0]->assigned_calc_ID = $request->input('assigned_calc_ID');
		$galaxy[0]->optical_u = $request->input('optical_u');
		$galaxy[0]->optical_v = $request->input('optical_v');
		$galaxy[0]->optical_g = $request->input('optical_g');
		$galaxy[0]->optical_r = $request->input('optical_r');
		$galaxy[0]->optical_i = $request->input('optical_i');
		$galaxy[0]->optical_z = $request->input('optical_z');
		$galaxy[0]->infrared_three_six = $request->input('infrared_three_six');
		$galaxy[0]->infrared_four_five = $request->input('infrared_four_five');
		$galaxy[0]->infrared_five_eight = $request->input('infrared_five_eight');
		$galaxy[0]->infrared_eight_zero = $request->input('infrared_eight_zero');
		$galaxy[0]->infrared_J = $request->input('infrared_J');
		$galaxy[0]->infrared_H = $request->input('infrared_H');
		$galaxy[0]->infrared_K = $request->input('infrared_K');
		$galaxy[0]->radio_one_four = $request->input('radio_one_four');
		$galaxy[0]->toJson();

		$galaxy[1] = new redshifts();
		$galaxy[1]->job_id = $jobId;
		$galaxy[1]->methods = $request->input('methods');
		//todo - this is reliant on guest being id 1 in the users table.
		$galaxy[1]->user_ID = 1;

		//todo - this is reliant on guest being id 1 in the users table.
		$userEmail = User::select('email')->where('id', 1)->first();
		$mergeData = $userEmail . " : " . random_bytes(32);
		$cipherMethod = "aes-128-cbc";
		$key = "5rCBIs9Km!!cacr1";
		$iv = "123hasdba036vpax";
		$tokenData = openssl_encrypt($mergeData, $cipherMethod, $key, $options=0, $iv);
		$galaxy[1]->token = $tokenData;
		$galaxy[1]->toJson();

		//setting up all required API data to send via JSON
		$dataJSON = $galaxy;
		////initialising the guzzle client
		$urlAPI = 'https://redshift-01.cdms.westernsydney.edu.au/redshift/api/';
		$client = new Client(['base_uri' => $urlAPI, 'verify' => false, 'exceptions' => false, 'http_errors' => false]);
		////writing the code to send data to the API
		try{
			$client->request('POST', '', ['json' => $dataJSON]);
		}
		catch(\GuzzleHttp\Exception\ConnectException $e){
			return back()->withErrors("Upload failed. Try again later.");
		}

		$request->session()->forget('jobId');
		$request->session()->put('jobId', $jobId);
		return redirect('guestResult');
    }


    public function guestAjax(Request $request){
		if($request->session()->exists('jobId')){
			$job = $request->session()->get('jobId');
		}
		else{
			return redirect('guest');
		}

		$status = DB::select("SELECT status FROM redshifts
			INNER JOIN jobs on redshifts.job_id = jobs.job_id
			WHERE jobs.job_id = " . $job);
		if(isset($status[0]->status)){
			if($status[0]->status == "COMPLETED" || $status[0]->status == "READ" ){
				$result = DB::select("SELECT redshift_result, redshift_alt_result FROM calculations
				INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id
				INNER JOIN jobs on redshifts.job_id = jobs.job_id
				WHERE jobs.job_id = " . $job);
				$resultArray = [];

				if(isset($result[0]->redshift_result)){
					$resultArray[0] = $result[0]->redshift_result;
				}
				if(isset($result[0]->redshift_alt_result)){
					$resultArray[1] = $result[0]->redshift_alt_result;
				}
				return($resultArray);
			}
			else{
				return "WAITING";
			}
		}
		else{
			return "WAITING";
		}
	}

	public function guestResult(Request $request){
    	if($request->session()->exists('jobId')){
			$job = $request->session()->get('jobId');
		}
    	else{
    		return redirect('guest');
		}

		$result = 0;

		$status = DB::select("SELECT status FROM redshifts
			INNER JOIN jobs on redshifts.job_id = jobs.job_id
			WHERE jobs.job_id = " . $job);
		if(isset($status[0]->status)){
			if($status[0]->status == "COMPLETED" || $status[0]->status == "READ" ){
				$result = DB::select("SELECT redshift_result, redshift_alt_result FROM calculations
				INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id
				INNER JOIN jobs on redshifts.job_id = jobs.job_id
				WHERE jobs.job_id = " . $job);
				//return($result[0]);
			}
			else{
				//return($status[0]->status);
			}
		}
		else{
			//return "WAITING";
		}
		$jobId = $job;
		return view('result', compact('status', 'result', 'jobId'));
	}


}
