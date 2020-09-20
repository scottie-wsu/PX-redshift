<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\redshifts;
use App\methods;
use App\calculations;

class RedshiftController extends Controller
{
	public function custom()
	{
		$redshifts = calculations::select('redshift_result')->groupBy('redshift_result')->get();
        $redshiftsPluck = collect($redshifts)->pluck('redshift_result');
        $redshiftCount = calculations::select('redshift_result', DB::raw('COUNT(*) as total'))->groupBy('redshift_result')->orderBy('redshift_result')->get();
        $redshiftArray = $redshiftCount->pluck('total')->all();

        $chartjs3 = app()->chartjs

			->name('lineChartTest')
			->type('bar')
			->size(['width' => 400, 'height' => 400])
			->labels($redshiftsPluck->keys()->toArray())
			->datasets([
				[
					"label" => "Redshifts Completed",
					"yAxisID" => "A",
					'backgroundColor' => "rgba(38, 185, 154, 0.31)",
					'borderColor' => "rgba(38, 185, 154, 0.7)",
					"pointBorderColor" => "rgba(38, 185, 154, 0.7)",
					"pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
					"pointHoverBackgroundColor" => "#fff",
					"pointHoverBorderColor" => "rgba(220,220,220,1)",
					//not sure if toArray is necessary but it works either way
					'data' => $redshiftArray->toArray(),

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
		


		return view('analytics', compact('chartjs3'));
		//return(dump($label));
	}


}
