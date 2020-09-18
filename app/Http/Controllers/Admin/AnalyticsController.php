<?php

namespace App\Http\Controllers\Admin;
use App\calculations;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\redshifts;

class AnalyticsController extends Controller
{
	public function custom()
	{
		$institutionCount = User::select('institution', DB::raw('count(*) as total'))->groupBy('institution')->get();
		$institutionCountTotal = $institutionCount->pluck('total');
		$institutionLabels = User::orderBy('created_at')->pluck('id', 'institution');
		//dump($institutionLabels->keys()->toArray());
		//dump($institutionCountTotal->toArray());

		$jobCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY users.institution');

		$jobCountPerUser = DB::select('SELECT name, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY redshifts.user_id');





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
          					max: 10,
          					min: 0
						}
					}, {
        				id: 'B',
        				type: 'linear',
        				position: 'right',
						ticks: {
          					max: 110,
          					min: 0
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
				/*

				[
					"label" => "Jobs completed",
					"yAxisID" => "B",
					'backgroundColor' => "rgba(200, 34, 154, 0.7)",
					'borderColor' => "rgba(200, 34, 154, 0.7)",
					"pointBorderColor" => "rgba(200, 34, 154, 0.7)",
					"pointBackgroundColor" => "rgba(200, 34, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					'data' => collect($jobCountPerUser)->pluck('total')->toArray(),
				]
				*/

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
          					max: 100,
          					min: 0
						}
					}, {
        				id: 'B',
        				type: 'linear',
        				position: 'right',
						ticks: {
          					max: 100,
          					min: 0
						}
      					}]
				}
			}");


		return view('analytics', compact('chartjs','chartjs1'));
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
        				id: 'B',
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
