<?php

namespace App\Http\Controllers\Admin;
use App\calculations;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\redshifts;
use App\methods;

class AnalyticsController extends Controller
{
	public function custom()
	{
		$institutionCount = User::select('institution', DB::raw('count(*) as total'))->groupBy('institution')->get();
		$institutionCountTotal = $institutionCount->pluck('total');
		$institutionLabels = User::orderBy('created_at')->pluck('id', 'institution');
		//dump($institutionLabels->keys()->toArray());
		//dump($institutionCountTotal->toArray());

		$jobCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN jobs on users.id = jobs.user_id GROUP BY users.institution');

		$jobCountPerUser = DB::select('SELECT name, COUNT(*) as total FROM users INNER JOIN jobs on users.id = jobs.user_id GROUP BY jobs.user_id');





		$chartjs = app()->chartjs

			->name('lineChartTest')
			->type('bar')
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
					'data' => $institutionCountTotal->toArray(),
				],

				[
					"label" => "Jobs completed per institution",
					"yAxisID" => "B",
					'backgroundColor' => "rgba(200, 34, 154, 0.7)",
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
		$jobCountPerMethod = DB::select('SELECT method_name, COUNT(*) as total FROM calculations INNER JOIN methods on calculations.method_id = methods.method_id GROUP BY methods.method_id');

		$method_name = methods::orderBy('method_id')->pluck('method_id', 'method_name');
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

		$chartjs2 = app()->chartjs
			->name('pieChartTest')
			->type('pie')
			->size(['width' => 400, 'height' => 200])
			->labels($method_name->keys()->toArray())
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

		//todo - REMEMBER THAT REDSHIFT_RESULT MAY NOT BE A SINGLE NUMBER IN THE FINAL VERSION - NEED TO IMPLEMENT WHERE RESULT IS NUMERIC CODE
		//todo - this is the finished queries for left/right data
		//$jobCountPerUser = DB::select('SELECT user_id, COUNT(*) as total FROM jobs INNER JOIN users on jobs.user_id = users.id GROUP BY users.id');
		//$calculationCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY users.institution');
		//$userPerInstitutionCount = DB::select('SELECT institution, COUNT(*) as total FROM users GROUP BY users.institution');
		//todo - this is the end of the finished queries

		//todo - this one requires the most work probably - need to recreate labels variable to pull each redshift result (easy) then put these results into bins
		//$redshiftResultsPerInstitution = DB::select('SELECT redshift_result, COUNT(*) as total FROM calculations INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id GROUP BY calculations.redshift_result;');




		//$institutionCount = User::select('institution', DB::raw('count(*) as total'))->groupBy('institution')->get();
		//$institutionCountTotal = $institutionCount->pluck('total');

		$institutionLabels = calculations::orderBy('redshift_result')->pluck('real_calculation_id', 'redshift_result');
		//$institutionLabels = User::orderBy('created_at')->pluck('id', 'institution');
		$userPerInstitutionCount = DB::select('SELECT institution, COUNT(*) as total FROM users GROUP BY users.institution');
		$redshiftResultsPerInstitution = DB::select('SELECT redshift_result, COUNT(*) as total FROM calculations INNER JOIN redshifts on calculations.galaxy_id = redshifts.calculation_id GROUP BY calculations.redshift_result');





		$calculationCountPerInstitution = $redshiftResultsPerInstitution;


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
					"label" => "Jobs completed per institution",
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
        				id: 'B',z
        				type: 'linear',
        				position: 'right',

      					}]
				}
			}");





		//dump($leftAxisDataTest
		return view('plotting', compact('chartjs'));
	}

	public function ajaxCounts(){
		$submitted = redshifts::select('calculation_id')->where('status', 'SUBMITTED')->get()->count();
		$processing = redshifts::select('calculation_id')->where('status', 'PROCESSING')->get()->count();
		$result = [$submitted, $processing];
		echo $result[0];
	}

}
