<?php

namespace App\Http\Controllers\Admin;
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
		// uses per institution chart \\
		$institutionCount = User::select('institution', DB::raw('count(*) as total'))->groupBy('institution')->get();
		$institutionCountTotal = $institutionCount->pluck('total');

		//label\\
		$institutionLabels = User::orderBy('created_at')->pluck('id', 'institution');
		//dump($institutionLabels->keys()->toArray());
		//dump($institutionCountTotal->toArray());

		// for users per institution \\
		$jobCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY users.institution');

		// for jobs completed chart\\
		$jobCountPerUser = DB::select('SELECT name, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY redshifts.user_id');

		// method used pie chart \\

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

        $outlineArray = [];
        for($i=0;$i<count($colorArray);$i++){
            $outlineArray[$i] = 'rgba(1, 1, 1, 1)';
        }

        $jobCountPerMethod = DB::select('SELECT method_name, COUNT(*) as total FROM calculations INNER JOIN methods on calculations.method_id = methods.method_id GROUP BY methods.method_id');





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
          					min: 0
						}
						
					}, {
        				id: 'B',
        				type: 'linear',
        				position: 'right',
        				ticks: {
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
					"label" => "Jobs Completed",
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
          					min: 0
						}
					}]
				}
			}");

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
			
			


		return view('analytics', compact('chartjs','chartjs1','chartjs2'));
		//return(dump($label));
	}

}
