<?php

namespace App\Http\Controllers\Admin;
use App\calculations;
use GuzzleHttp\Client;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\redshifts;
use App\methods;
use App\Jobs;


class AnalyticsController extends Controller
{
	public function custom()
	{
		//this is borked on my local machine but works fine on the server. SQL sucks
		//$institutionCount = User::select('institution', DB::raw('count(*) as total'))->distinct('institution')->groupBy('institution')->orderBy('created_at')->get();
		$institutionCount = User::select('institution', DB::raw('count(*) as total'))->distinct('institution')->groupBy('institution')->get();

		$institutionCountTotal = $institutionCount->pluck('total');
		$institutionLabels = User::orderBy('created_at')->pluck('id', 'institution');
		//dump($institutionLabels->keys()->toArray());
		//dump($institutionCountTotal->toArray());

		$jobCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN jobs on users.id = jobs.user_id GROUP BY users.institution');

		$jobCountPerUser = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN jobs on users.id = jobs.user_id GROUP BY users.institution');





		$chartjs = app()->chartjs

			->name('lineChartTest')
			->type('bar')
			->size(['width' => 400, 'height' => 400])
			->labels($institutionLabels->keys()->toArray())
			->datasets([
				[
					"label" => "Users per institution",
					"yAxisID" => "A",
					'backgroundColor' => "rgba(38, 185, 154, 1)",
					'borderColor' => "rgba(38, 185, 154, 0.7)",
					"pointBorderColor" => "rgba(38, 185, 154, 0.7)",
					"pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					//not sure if toArray is necessary but it works either way
					'data' => $institutionCountTotal->toArray(),
				],

				[
					"label" => "Jobs completed per institution",
					"yAxisID" => "B",
					'backgroundColor' => "rgba(200, 34, 154, 1)",
					'borderColor' => "rgba(200, 34, 154, 0.7)",
					"pointBorderColor" => "rgba(200, 34, 154, 0.7)",
					"pointBackgroundColor" => "rgba(200, 34, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					'data' => collect($jobCountPerInstitution)->pluck('total')->toArray(),
				]

			])
			//for some reason it needs the ticks values to be set
			//todo - find the max value in the datasets and set max value to that
			->optionsRaw("{
    			scales: {
      				yAxes: [{
        				id: 'A',
        				type: 'linear',
        				position: 'left',
						ticks: {
							beginAtZero: true,
							stepSize: 1
						},
						 scaleLabel: {
                                display: true,
                                labelString: 'Users per institution',
                                fontColor: 'rgba(38, 185, 154, 2)'
                            },

					},
					{
        				id: 'B',
        				type: 'linear',
        				position: 'right',
						ticks: {
							beginAtZero: true
						},
						gridLines: {
        					display: false,
      					},
						scaleLabel: {
                                display: true,
                                labelString: 'Jobs completed per institution',
                                fontColor: 'rgba(200, 34, 154, 2)'
                            }
      					}]
				}
			}");

		$chartjs1 = app()->chartjs

			->name('lineChartTest1')
			->type('bar')
			->size(['width' => 400, 'height' => 400])
			->labels($institutionLabels->keys()->toArray())
			->datasets([
				[
					"label" => "Jobs completed",
					"yAxisID" => "A",
					'backgroundColor' => "rgba(38, 185, 154, 0.31)",
					'borderColor' => "rgba(38, 185, 154, 0.7)",
					"pointBorderColor" => "rgba(38, 185, 154, 0.7)",
					"pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					//not sure if toArray is necessary but it works either way
					'data' => collect($jobCountPerUser)->pluck('total')->toArray(),
				],

			])
			//for some reason it needs the ticks values to be set
			//todo - find the max value in the datasets and set max value to that
			->optionsRaw("{
    			scales: {
      				yAxes: [{
        				id: 'A',
        				type: 'linear',
        				position: 'left',
						ticks: {
							beginAtZero: true
						},
						scaleLabel: {
                                display: true,
                                labelString: 'Amount of Jobs completed',
                                fontColor: 'rgba(38, 185, 154, 2)'

                            }

					}]
				}
			}");

		//
		//method breakdown chart
		//
		$jobCountPerMethod = DB::select('SELECT method_name, COUNT(*) as total FROM calculations INNER JOIN methods on calculations.method_id = methods.method_id GROUP BY methods.method_name ORDER BY methods.method_id');

		$methodRemovedArray = methods::select('removed')->get();

		//$method_name = methods::orderBy('method_id')->pluck('method_id', 'method_name');
		$methodLabelArray = methods::select('method_name', 'removed')->get();

		$methodLabels = [];
		foreach ($methodLabelArray as $method)
		{
			if($method->removed == 1)
			{
				$methodLabels[] = $method->method_name . "*";
			}
			else{
				$methodLabels[] = $method->method_name;
			}
		}


		//dump($methodLabelArray);

		$colorArray =[
			'rgba(255, 40, 31, 0.6)', //red
			'rgba(255, 242, 31, 0.6)', //yellow
			'rgba(31, 31, 255, 0.6)', //blue
			'rgba(141, 31, 187, 0.8)', //purple
			'rgba(31, 187, 31, 0.8)', //green
			'rgba(255, 165, 31, 0.8)', //orange
			'rgba(244, 31, 224, 0.8)', //pink
			'rgba(31, 187, 212, 0.8)', //aqua
			'rgba(255, 207, 31, 0.8)', //light orange
			'rgba(255, 125, 31, 0.8)', //dark orange
			'rgba(133, 31, 255, 0.8)', //dark purple
			'rgba(131, 206, 31, 0.8)', //bright green
		];

		$index = 0;
		foreach($methodRemovedArray as $method)
		{
			if(	$method->removed == 1)
			{
				$colorValue = rand(150, 220);
				$colorArray[$index] = 'rgba('.$colorValue.','.$colorValue.','.$colorValue.')';
				//dump($colorValue);
			}
			$index++;
		}

		$chartjs2 = app()->chartjs
			->name('pieChartTest')
			->type('pie')
			->size(['width' => 400, 'height' => 200])
			->labels($methodLabels)
			->datasets([
				[
					"label" => "Methods Used",
					'backgroundColor' => ($colorArray),
					'hoverBackgroundColor' => [],
					'data' => collect($jobCountPerMethod)->pluck('total')->toArray(),
				]
			])
			->options([]);

		//
		//redshift result chart
		//
		$redshiftResultsPerInstitution = DB::select('SELECT redshift_result FROM calculations INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id GROUP BY calculations.redshift_result');

		//counting total redshifts
		$redshiftTotalCount = count($redshiftResultsPerInstitution);
		//creating a new array with no associativity/keys, just values
		$newArray = array_column($redshiftResultsPerInstitution, 'redshift_result');
		//sorting the new array into ascending numerical order
		sort($newArray);
		$arraySliced = array();

		//figuring out how many bins. Using Sturges' formula
		$binSize = round((log($redshiftTotalCount, 2))+1);
		//bincount here represents what range of values goes into one bin.
		//e.g. binsize of 10 means bincount = 1, so 0-1, 1-2, etc are bins
		$binCount = 10/$binSize;
		for($i=0; $i<$binSize-1; $i++){
			$arraySliced[0] = 0;
			//looping over all the sorted redshifts
			for($j=0; $j<$redshiftTotalCount; $j++){
				//probably a better way to do this.
				//writes arraysliced on every loop, which is what defines
				//our bin cutoff points in terms of index
				if($newArray[$j] <= $binCount*($i+1)){
					$arraySliced[$i+1] = $j+1;
				}
			}
		}

		//building the array of data, which is simply the count of results in each bin
		$arrayFinal = array();

		for($i=0;$i<$binSize;$i++){
			if($i<$binSize-1){
				//slicelength is counting how many results are in each bin
				$sliceLength = $arraySliced[$i+1]-$arraySliced[$i];
			}
			else{
				//this is the case for the final bin length
				$sliceLength = $redshiftTotalCount-$arraySliced[$i];
			}
			//finally, for our final values for each bin, we just take
			//write each bin's slicelength (count) into an array
			$arrayFinal[$i] = $sliceLength;
		}


		$binLabels = array();
		for($i=0;$i<=$binSize+1;$i++){
			if($i==0){
				$binLabels[$i] = 0;
			}
			else{
				$binLabels[$i] = round(($binCount*$i), 2);
			}
		}


		$binLabelsMax = $binLabels[$binSize-1];

		$chartjs3 = app()->chartjs

			->name('lineChartTest3')
			->type('bar')
			->size(['width' => 400, 'height' => 200])
			//->labels($institutionLabels1->keys()->toArray())
			->labels($binLabels)
			->datasets([
				[
					"label" => "Redshift result frequency",
					'backgroundColor' => "rgba(200, 34, 154, 0.7)",
					'borderColor' => "rgba(200, 34, 154, 0.0)",
					"pointBorderColor" => "rgba(200, 34, 154, 0.7)",
					"pointBackgroundColor" => "rgba(200, 34, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,0.0)",
					'barPercentage' => 1.0,
					'categoryPercentage' => 1.0,
					'data' => $arrayFinal,
				]

			])
			->optionsRaw("{
    			scales: {
      				yAxes: [{
        				id: 'A',
        				type: 'linear',
						ticks: {
							beginAtZero: true
						}
					}],
				  xAxes: [{
					display: false,
					ticks: {
						max: " . ($binLabelsMax) . ",
					}
					 }, {
						display: true,
						ticks: {
							autoSkip: false,
							max: " . ($binLabels[$binSize]) . ",
						}
					  }],
				},
								tooltips: {
					titleFontSize: 16,
					callbacks: {
						title: function(tooltipItem, data) {
							var increment = ". $binLabels[1] .";
							var calc = (tooltipItem[0].xLabel)+increment;
							return tooltipItem[0].xLabel + ' - ' + calc.toFixed(2);
						}
					}
				}

			}");

		$charts = [$chartjs, $chartjs1, $chartjs2, $chartjs3];

		return view('analytics', compact('charts'));
		//return(dump($label));
	}



	public function plotting(){
		$test = 'test';
		return view('plotting', compact($test));
	}


	public function plotDataPost(Request $request){

		//dump($request->request);
		$chartType = $request->input('chartType');
		$leftAxisData = $request->input('leftAxisData');
		$leftAxisArray = explode("Left", $leftAxisData);
		$left = $leftAxisArray[0]; //jobCount/usersCount/redshiftResults/CalculationCount

		$rightAxisData = $request->input('rightAxisData');
		$rightAxisArray = explode("Right", $rightAxisData);
		$right = $rightAxisArray[0]; //jobCount/usersCount/redshiftResults/CalculationCount

		$perData = $request->input('perData');
		$perArray = explode("Per", $perData);
		$per = $perArray[0]; //institution/job/user/day/method


		$chartType = $request->input('chartType') . PHP_EOL;
		dump($chartType);
		$rightAxisData = $request->input('rightAxisData') . PHP_EOL;

		$perData = $request->input('perData') . PHP_EOL;


		$jobCountPerUser = DB::select("select count(jobs.job_id) as 'job count', users.id from jobs, users where jobs.user_id = users.id GROUP by users.id");
		$jobCountPerInstitution = DB::select("select count(jobs.job_id) as 'job count', users.institution from jobs, users where jobs.user_id = users.id GROUP by users.institution");
		$userCountPerInstitution = DB::select("select count(users.id) as 'users_count', users.institution from users GROUP by users.institution");
		$calculationCountPerUser = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', users.id from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.id");
		$calculationCountPerJob = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', redshifts.job_id from redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id GROUP by redshifts.job_id");
		$calculationCountPerInstitution = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', users.institution from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.institution");
		$calculationCountPerMethod = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', calculations.method_id from calculations GROUP by calculations.method_id");

		// var_dump($jobCountPerUser);

		// //todo - REMEMBER THAT REDSHIFT_RESULT MAY NOT BE A SINGLE NUMBER IN THE FINAL VERSION - NEED TO IMPLEMENT WHERE RESULT IS NUMERIC CODE
		// //todo - this is the finished queries for left/right data
		// //
		// //$calculationCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY users.institution');
		// //$userPerInstitutionCount = DB::select('SELECT institution, COUNT(*) as total FROM users GROUP BY users.institution');
		// //todo - this is the end of the finished queries

		// //todo - this one requires the most work probably - need to recreate labels variable to pull each redshift result (easy) then put these results into bins
		// //$redshiftResultsPerInstitution = DB::select('SELECT redshift_result, COUNT(*) as total FROM calculations INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id GROUP BY calculations.redshift_result;');


		// $jobCountPerUser = DB::select('SELECT user_id, COUNT(*) as total FROM jobs INNER JOIN users on jobs.user_id = users.id GROUP BY users.id');

		// // $jobCountPerUser = DB::table('jobs')->select('user_id')->join("jobs.user_id", "users.id")->count();

		// //$institutionCount = User::select('institution', DB::raw('count(*) as total'))->groupBy('institution')->get();
		// //$institutionCountTotal = $institutionCount->pluck('total');

		// $institutionLabels = calculations::orderBy('redshift_result')->pluck('real_calculation_id', 'redshift_result');
		// //$institutionLabels = User::orderBy('created_at')->pluck('id', 'institution');
		// $userPerInstitutionCount = DB::select('SELECT institution, COUNT(*) as total FROM users GROUP BY users.institution');
		// $redshiftResultsPerInstitution = DB::select('SELECT redshift_result, COUNT(*) as total FROM calculations INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id GROUP BY calculations.redshift_result');

		// $calculationCountPerInstitution = $redshiftResultsPerInstitution;
		// $calculationCountPerInstitution = $jobCountPerUser;

		$chartjs = app()->chartjs

			->name('lineChartTest')
			->type($chartType)
			->size(['width' => 400, 'height' => 400])
			->labels($institutionLabels->keys()->toArray())
			->datasets([
				[
					"label" => "Users per institution",
					"yAxisID" => "A",
					'backgroundColor' => "rgba(38, 185, 154, 0.31)",
					'borderColor' => "rgba(38, 185, 154, 0.7)",
					"pointBorderColor" => "rgba(38, 185, 154, 0.7)",
					"pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					//not sure if toArray is necessary but it works either way
					'data' => collect($userPerInstitutionCount)->pluck('total')->toArray(),
				],

				[
					"label" => "Job count per institution",
					"yAxisID" => "B",
					'backgroundColor' => "rgba(200, 34, 154, 0.7)",
					'borderColor' => "rgba(200, 34, 154, 0.7)",
					"pointBorderColor" => "rgba(200, 34, 154, 0.7)",
					"pointBackgroundColor" => "rgba(200, 34, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					'data' => collect($calculationCountPerInstitution)->pluck('total')->toArray(),
				]

			])
			//for some reason it needs the ticks values to be set
			//todo - find the max value in the datasets and set max value to that
			->optionsRaw("{
    			scales: {
      				yAxes: [{
        				id: 'A',
        				type: 'linear',
        				position: 'left',

					}, {
        				id: 'B',
        				type: 'linear',
        				position: 'right',

      					}]
				}
			}");





		//dump($leftAxisDataTest
		return view('plotting', compact('chartjs'));
	}


	public function ajaxCounts1(){
		$submitted = redshifts::select('calculation_id')->where('status', 'SUBMITTED')->get()->count();

		$result = $submitted;
		echo $result;
	}

	public function ajaxCounts2(){
		$processing = redshifts::select('calculation_id')->where('status', 'PROCESSING')->get()->count();

		$result = $processing;
		echo $result;
	}

	public function ajaxCounts3(){
		$jobCount = Jobs::select('job_id')->get()->count();
		$result = $jobCount;
		echo $result;
	}

	public function ajaxCounts4(){
		$redshiftCount = redshifts::select('calculation_id')->get()->count();

		$result = $redshiftCount;
		echo $result;
	}

	public function ajaxCounts5(){
		$usersCount = User::select('id')->get()->count();

		$result = $usersCount;
		echo $result;
	}

	public function ajaxCounts6(){
		$methodCount = methods::select('method_id')->where('removed', '0')->get()->count();

		$result = $methodCount;
		echo $result;
	}

	public function ajaxCounts7(){
		////initialising the guzzle client
		$dataJSON = new redshifts();
		$dataJSON->token = "bWP64ux77I1l8R45gYtn8JwLBLw9lFoaRLKEGVh/kPClKKYDkRvgDJD93iTGf5Iz";
		$urlAPI = 'https://redshift-01.cdms.westernsydney.edu.au/redshift/api/system-load/';
		$client = new Client(['base_uri' => $urlAPI, 'verify' => false, 'exceptions' => false, 'http_errors' => false]);
		////writing the code to send data to the API
		try{
			$response = $client->request('POST', '', ['json' => $dataJSON]);
		}
		catch(\GuzzleHttp\Exception\ConnectException $e){
			return "Connection error";
		}
		if($response->getStatusCode() != 200){
			return "Request error ".$response->getStatusCode();
		}
		$string = (string)$response->getBody()->getContents();
		$load = json_decode($string, true);
		$fiveMinutes = $load['system-load'][0];
		$fiveMinutes = $fiveMinutes*100;
		return "System load last 1 minute: ". $fiveMinutes . "%";
	}

	public function ajaxCounts8(){
		////initialising the guzzle client
		$dataJSON = new redshifts();
		$dataJSON->token = "bWP64ux77I1l8R45gYtn8JwLBLw9lFoaRLKEGVh/kPClKKYDkRvgDJD93iTGf5Iz";
		$urlAPI = 'https://redshift-01.cdms.westernsydney.edu.au/redshift/api/system-load/';
		$client = new Client(['base_uri' => $urlAPI, 'verify' => false, 'exceptions' => false, 'http_errors' => false]);
		////writing the code to send data to the API
		try{
			$response = $client->request('POST', '', ['json' => $dataJSON]);
		}
		catch(\GuzzleHttp\Exception\ConnectException $e){
			return "Connection error";
		}
		if($response->getStatusCode() != 200){
			return "Request error ".$response->getStatusCode();
		}
		$string = (string)$response->getBody()->getContents();
		$load = json_decode($string, true);
		$seconds30 = $load['system-load'][2];
		$seconds30 = $seconds30*100;
		return "System load last 15 minutes: ". $seconds30 . "%";
	}

}
