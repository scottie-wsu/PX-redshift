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
		//creating data to send in http json request
		//doing this before the method check so we can return all the previous inputs if they forget the method
		//and the method check fails
		$assigned_calc_ID = $request->input('assigned_calc_ID');
		$optical_u = floatval($request->input('optical_u'));
		$optical_v = floatval($request->input('optical_v'));
		$optical_g = floatval($request->input('optical_g'));
		$optical_r = floatval($request->input('optical_r'));
		$optical_i = floatval($request->input('optical_i'));
		$optical_z = floatval($request->input('optical_z'));
		$infrared_three_six = floatval($request->input('infrared_three_six'));
		$infrared_four_five = floatval($request->input('infrared_four_five'));
		$infrared_five_eight = floatval($request->input('infrared_five_eight'));
		$infrared_eight_zero = floatval($request->input('infrared_eight_zero'));
		$infrared_J = floatval($request->input('infrared_J'));
		$infrared_H = floatval($request->input('infrared_H'));
		$infrared_K = floatval($request->input('infrared_K'));
		$radio_one_four = floatval($request->input('radio_one_four'));

		$request->session()->forget('assigned_calc_ID');
		$request->session()->put('assigned_calc_ID', $assigned_calc_ID);
		$request->session()->forget('optical_u');
		$request->session()->put('optical_u', $optical_u);
		$request->session()->forget('optical_v');
		$request->session()->put('optical_v', $optical_v);
		$request->session()->forget('optical_g');
		$request->session()->put('optical_g', $optical_g);
		$request->session()->forget('optical_r');
		$request->session()->put('optical_r', $optical_r);
		$request->session()->forget('optical_i');
		$request->session()->put('optical_i', $optical_i);
		$request->session()->forget('optical_z');
		$request->session()->put('optical_z', $optical_z);
		$request->session()->forget('infrared_three_six');
		$request->session()->put('infrared_three_six', $infrared_three_six);
		$request->session()->forget('infrared_four_five');
		$request->session()->put('infrared_four_five', $infrared_four_five);
		$request->session()->forget('infrared_four_five');
		$request->session()->put('infrared_four_five', $infrared_four_five);
		$request->session()->forget('infrared_five_eight');
		$request->session()->put('infrared_five_eight', $infrared_five_eight);
		$request->session()->forget('infrared_eight_zero');
		$request->session()->put('infrared_eight_zero', $infrared_eight_zero);
		$request->session()->forget('infrared_J');
		$request->session()->put('infrared_J', $infrared_J);
		$request->session()->forget('infrared_H');
		$request->session()->put('infrared_H', $infrared_H);
		$request->session()->forget('infrared_K');
		$request->session()->put('infrared_K', $infrared_K);
		$request->session()->forget('radio_one_four');
		$request->session()->put('radio_one_four', $radio_one_four);

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

		$methods = $request->input('methods');
		$request->session()->forget('methods');
		$request->session()->put('methods', $methods);

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
    	if($request->session()->exists('assigned_calc_ID')){
			$assigned_calc_ID = $request->session('assigned_calc_ID');
			$optical_u = $request->session('optical_u');
			$optical_v = $request->session('optical_v');
			$optical_g = $request->session('optical_g');
			$optical_r = $request->session('optical_r');
			$optical_i = $request->session('optical_i');
			$optical_z = $request->session('optical_z');
			$infrared_three_six = $request->session('infrared_three_six');
			$infrared_four_five = $request->session('infrared_four_five');
			$infrared_five_eight = $request->session('infrared_five_eight');
			$infrared_eight_zero = $request->session('infrared_eight_zero');
			$infrared_J = $request->session('infrared_J');
			$infrared_H = $request->session('infrared_H');
			$infrared_K = $request->session('infrared_K');
			$radio_one_four = $request->session('radio_one_four');
			$methods = $request->session('methods');
		}
    	else{
    		return redirect('guest');
		}

		return view('result', compact('assigned_calc_ID','optical_u', 'optical_v', 'optical_g',
			'optical_r', 'optical_i', 'optical_z', 'infrared_three_six', 'infrared_four_five', 'infrared_five_eight',
			'infrared_eight_zero', 'infrared_J', 'infrared_H', 'infrared_K', 'radio_one_four', 'methods'));
	}


}
