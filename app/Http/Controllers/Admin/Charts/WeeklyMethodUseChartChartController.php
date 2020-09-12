<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\methods;
use App\calculations;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class WeeklyMethodUseChartChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklyMethodUseChartChartController extends ChartController
{
    public function setup()
    {

        //pulled from laravel backpack demo newentrieschartcontroller
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $labels = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            if ($days_backwards == 1) {
            }
            $labels[] = $days_backwards.' days ago';
        }
        $this->chart->labels($labels);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/weekly-method-use-chart'));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {

        $calcQuery = calculations::select('method_id', DB::raw('count(*) as total'))->groupBy('method_id')->get();
        $calcCount = $calcQuery->count();
        $calcArray = array(array());


        for ($days_backwards = 6; $days_backwards >= 0; $days_backwards--) {
            for($i=0; $i<$calcCount; $i++){

                $i1 = $i+1;
                $calcArray[$i][$days_backwards] = DB::select("SELECT method_id, COUNT(*) AS total FROM calculations WHERE created_at > ' " . Carbon::today()->subDays($days_backwards) . " ' AND created_at < ' " . Carbon::today()->subDays($days_backwards-1) . " ' AND method_id = '" . $i1 . "' GROUP BY created_at");
                $calcArray[$i][$days_backwards] = collect($calcArray[$i][$days_backwards])->pluck('total')->toArray();
            }
        }

        //dump($calcArray);




        $calcArray0 = $calcArray[0];
        $calcArray1 = $calcArray[1];
        $calcArray2 = $calcArray[2];



        $this->chart->dataset('Jobs completed', 'line', $calcArray0)
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor('rgba(205, 32, 31, 0.4)');

        $this->chart->dataset('Jobs completed', 'line', $calcArray1)
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor('rgba(205, 32, 31, 0.4)');

        $this->chart->dataset('Jobs completed', 'line', $calcArray2)
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor('rgba(205, 32, 31, 0.4)');

    }
}
