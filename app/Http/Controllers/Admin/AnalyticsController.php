<?php

namespace App\Http\Controllers\Admin;
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
}
