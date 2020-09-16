<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\methods;
use App\calculations;
use Illuminate\Support\Facades\DB;

/**
 * Class MethodUseChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MethodUseChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $method_name = methods::orderBy('method_id')->pluck('method_id', 'method_name');
        //$methods = meth::orderBy('calculations.created_at')->pluck('calculations.real_calculation_id', 'method');

        $this->chart->labels($method_name->keys());



        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/method-use'));

        // OPTIONAL
         $this->chart->minimalist(true);
         $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */

     public function data()
     {
         //this is super ugly but colour quantization is HARD.
         //Need to find out expected magnitude of number of
         //methods that may be added in future.
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

         //can use basically raw sql in just the select command as we are not reading in any input from the user, so all data being entered into the query has already been vetted
         $jobCountPerMethod = DB::select('SELECT method_name, COUNT(*) as total FROM calculations INNER JOIN methods on calculations.method_id = methods.method_id GROUP BY methods.method_id');
         $this->chart->dataset('Methods', 'pie', collect($jobCountPerMethod)->pluck('total')->toArray())
             ->color($outlineArray)
             ->backgroundColor($colorArray);
     }
}
