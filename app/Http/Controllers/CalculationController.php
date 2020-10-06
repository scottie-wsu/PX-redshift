<?php

namespace App\Http\Controllers;

use App\redshifts;
use App\calculations;
use App\methods;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use App\Exports\RedshiftExport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use function MongoDB\BSON\toJSON;
use Illuminate\Support\Facades\Mail;
use App\Mail\CalcCompleteMail;
use App\Jobs;


class CalculationController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index(){

		return view('calculation');
	}

	public function export(){
		$str =  'redshift_result' . date('Y-m-d_h:m:s',time()) . '.csv';
		return Excel::download(new RedshiftExport, $str);
	}


	public function import(Request $request){
		$jobName = $request->input('job_nameFile');

		if(!(isset($jobName))){
			return back()->withErrors("Job name is a required field.");
		}

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


		//file upload logic
		$target_dir = "temp/";
		$clientFile = basename($_FILES["fileToUpload"]["name"]);
		$explodedClientFile = explode(".", $clientFile);
		$explodedCount = count($explodedClientFile);
		if($explodedClientFile[$explodedCount-1] != "csv"){
			return back()->withErrors("Filetype must be .csv.");
			//return \Redirect::route('home1')->with($msg);
		}

		$target_file = $target_dir . $clientFile;
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
		$file = fopen($target_file, "r");
		$lineCount = count(file($target_file));
		$galaxy =  array();
		$i = 0;
		$lineCounter = 0;

		//creating job entry in the database
		$userId = auth()->id();
		$job = new Jobs();
		$job->job_name = $request->input('job_nameFile');
		$job->job_description = $request->input('job_descriptionFile');
		$job->user_id = $userId;
		$job->save();
		$lastJob = DB::table('jobs')->latest('job_id')->where('user_id','=', $userId)->first();
		$jobId = $lastJob->job_id;

		while($lineCounter < $lineCount){
			$skipFlag = 0;
			$data = fgetcsv($file, $delimiter= ",");
			//checking if first data element is numeric so that a header row isn't submitted
			if(is_numeric($data[1])){
				//this logic checks that every data column has a value, and that value is a number
				for($x=1;$x<=14;$x++){
					if(!(isset($data[$x]) && is_numeric($data[$x]))){
						$skipFlag = 1;
					}
				}


				//is it worth finding some way to notify a user some rows were invalid?
				if($skipFlag != 1){
					$galaxy[$i] = new redshifts();
					$galaxy[$i]->assigned_calc_ID = $data[0];
					$galaxy[$i]->optical_u = floatval($data[1]);
					$galaxy[$i]->optical_v = floatval($data[2]);
					$galaxy[$i]->optical_g = floatval($data[3]);
					$galaxy[$i]->optical_r = floatval($data[4]);
					$galaxy[$i]->optical_i = floatval($data[5]);
					$galaxy[$i]->optical_z =  floatval($data[6]);
					$galaxy[$i]->infrared_three_six = floatval($data[7]);
					$galaxy[$i]->infrared_four_five =  floatval($data[8]);
					$galaxy[$i]->infrared_five_eight =  floatval($data[9]);
					$galaxy[$i]->infrared_eight_zero =  floatval($data[10]);
					$galaxy[$i]->infrared_J =  floatval($data[11]);
					$galaxy[$i]->infrared_H =  floatval($data[12]);
					$galaxy[$i]->infrared_K =  floatval($data[13]);
					$galaxy[$i]->radio_one_four =  floatval($data[14]);
					$galaxy[$i]->toJson();
					$i++;
				}
				else{
					//makes more sense to just tell the user immediately rather than them having to resubmit a file with successful galaxies already submitted omitted
					return back()->withErrors("All fields are required and all fields except galaxy ID must be numeric. Check your file and try again. File upload failed at line " . ($lineCounter+1));
				}

			}
			else{
				$csvHeader = true;
			}
			$lineCounter++;
		}
		fclose($file);



		$arrayCount = count($galaxy);

		//only create a request if there is at least 1 valid galaxy created in the loop above
		if($arrayCount > 0){
			//creating metadata row for API
			$galaxy[$arrayCount] = new redshifts();
			$galaxy[$arrayCount]->job_id = $jobId;
			$galaxy[$arrayCount]->methods = $methodRequests;
			$galaxy[$arrayCount]->user_ID = auth()->id();

			//creating token logic
			$userEmail = User::select('email')->where('id', auth()->id())->first();
			$userExtract = $userEmail['email'];
			$mergeData = $userExtract . ":" . random_bytes(32);
			$cipherMethod = "aes-128-cbc";
			$key = "5rCBIs9Km!!cacr1";
			$iv = "123hasdba036vpax";
			$tokenData = openssl_encrypt($mergeData, $cipherMethod, $key, $options=0, $iv);
			//$tokenData = 'bWP64ux77I1l8R45gYtn8JwLBLw9lFoaRLKEGVh/kPClKKYDkRvgDJD93iTGf5Iz';

			$galaxy[$arrayCount]->token = $tokenData;
			$galaxy[$arrayCount]->toJson();

			//setting up all required API data to send via JSON.
			//using a copied variable here in case more processing needs to be done in future
			$dataJSON = $galaxy;
			////initialising the guzzle client
			$urlAPI = 'http://127.0.0.1:5000';
			$client = new Client(['base_uri' => $urlAPI]);
			////writing the code to send data to the API
			$client->request('POST', '/', ['json' => $dataJSON]);

			return redirect('/history');
		}
		else{
			return back()->withErrors("At least one valid galaxy must be submitted.");
		}

	}

	//building /history page
	public function home(){
		$pages=20;


		//else{
		$calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
			->select('redshifts.*','calculations.redshift_result')->orderByDesc('calculations.updated_at')->where('redshifts.user_ID', auth()->id());
		//}

		$userId = auth()->id();
		//FROM calculations INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id
		$jobs = DB::select('SELECT job_id, job_name, job_description, user_id, created_at FROM jobs WHERE user_id = '.$userId);

		return view('history', compact('calculations', 'jobs'));
	}

	public function search(Request $req)
	{
		//
		$pages=20;
		$q = $req->input('q');
		$user = calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
			->where('jobs.user_ID', auth()->id())->where(function ($query) use ($q)  {
				$query->orWhere('assigned_calc_ID','LIKE','%'.$q.'%')
					->orWhere('redshifts.optical_u','LIKE','%'.$q.'%')->
					orWhere('redshifts.optical_v','LIKE','%'.$q.'%')->
					orWhere('optical_g','LIKE','%'.$q.'%')->
					orWhere('redshifts.optical_r','LIKE','%'.$q.'%')->
					orWhere('redshifts.optical_i','LIKE','%'.$q.'%')->
					orWhere('redshifts.optical_z','LIKE','%'.$q.'%')->
					orWhere('redshifts.infrared_three_six','LIKE','%'.$q.'%')->
					orWhere('redshifts.infrared_five_eight','LIKE','%'.$q.'%')->
					orWhere('redshifts.infrared_eight_zero','LIKE','%'.$q.'%')->
					orWhere('redshifts.infrared_J','LIKE','%'.$q.'%')->
					orWhere('redshifts.infrared_H','LIKE','%'.$q.'%')->
					orWhere('redshifts.infrared_K','LIKE','%'.$q.'%')->
					orWhere('redshifts.radio_one_four','LIKE','%'.$q.'%')->
					orWhere('calculations.redshift_result','LIKE','%'.$q.'%');
			})->paginate($pages);

		if(count($user) > 0){
			$details=1;
			return view('search',compact('user','details'));
		}
		else {
			return view ('search')->withMessage('No Details found. Try to search again!');
		}
	}

	public function store(Request $request){

		//creating job entry in the database
		$userId = auth()->id();
		$job = new Jobs();
		$job->job_name = $request->input('job_name');
		$job->job_description = $request->input('job_description');
		$job->user_id = $userId;
		$job->save();
		$lastJob = DB::table('jobs')->latest('job_id')->where('user_id','=', $userId)->first();
		$jobId = $lastJob->job_id;

		//creating data for http json request
		$galaxy = array();
		$galaxy[0] = new redshifts();
		$galaxy[0]->assigned_calc_ID = $request->input('assigned_calc_ID');
		$galaxy[0]->optical_u = floatval($request->input('optical_u'));
		$galaxy[0]->optical_v = floatval($request->input('optical_v'));
		$galaxy[0]->optical_g = floatval($request->input('optical_g'));
		$galaxy[0]->optical_r = floatval($request->input('optical_r'));
		$galaxy[0]->optical_i = floatval($request->input('optical_i'));
		$galaxy[0]->optical_z = floatval($request->input('optical_z'));
		$galaxy[0]->infrared_three_six = floatval($request->input('infrared_three_six'));
		$galaxy[0]->infrared_four_five = floatval($request->input('infrared_four_five'));
		$galaxy[0]->infrared_five_eight = floatval($request->input('infrared_five_eight'));
		$galaxy[0]->infrared_eight_zero = floatval($request->input('infrared_eight_zero'));
		$galaxy[0]->infrared_J = floatval($request->input('infrared_J'));
		$galaxy[0]->infrared_H = floatval($request->input('infrared_H'));
		$galaxy[0]->infrared_K = floatval($request->input('infrared_K'));
		$galaxy[0]->radio_one_four = floatval($request->input('radio_one_four'));
		$galaxy[0]->toJson();

		//creating metadata row for API
		$galaxy[1] = new redshifts();
		$galaxy[1]->job_id = $jobId;
		$galaxy[1]->methods = $request->input('methods');
		$galaxy[1]->user_ID = auth()->id();

		$userEmail = User::select('email')->where('id', auth()->id())->first();
		$userExtract = $userEmail['email'];
		$mergeData = $userExtract . ":" . random_bytes(32);
		$cipherMethod = "aes-128-cbc";
		$key = "5rCBIs9Km!!cacr1";
		$iv = "123hasdba036vpax";
		$tokenData = openssl_encrypt($mergeData, $cipherMethod, $key, $options=0, $iv);
		//$tokenData = 'bWP64ux77I1l8R45gYtn8JwLBLw9lFoaRLKEGVh/kPClKKYDkRvgDJD93iTGf5Iz';

		$galaxy[1]->token = $tokenData;
		$galaxy[1]->toJson();


		//setting up all required API data to send via JSON
		$dataJSON = $galaxy;
		////initialising the guzzle client
		$urlAPI = '127.0.0.1:5000';
		$client = new Client(['base_uri' => $urlAPI, 'verify' => false, 'exceptions' => false, 'http_errors' => false]);
		////writing the code to send data to the API
		try{
			$response = $client->request('POST', '/', ['json' => $dataJSON]);
		} catch(Exception $e) {
			//todo - return home alert with msg
			dd($e->getBody());
		}
		//todo - redirect to waiter page
		return redirect('/home');


	}
}
