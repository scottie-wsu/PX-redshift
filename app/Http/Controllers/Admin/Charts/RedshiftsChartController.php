<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\calculations;
use Illuminate\Support\Facades\DB;

/**
 * Class RedshiftsChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RedshiftsChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();
        //$redshifts = calculations::select('redshift_result')->groupBy('redshift_result')->get();
        //$redshiftsPluck = collect($redshifts)->pluck('redshift_result');

        // MANDATORY. Set the labels for the dataset points
        //$this->chart->labels([$redshiftsPluck->keys()]);
        //super ugly but it was the only thing stopping it from working for the prototype
        $this->chart->labels(['3.83', '8.75', '91.0', '105.0', '106.0']);
        $this->chart->options(['barPercentage' => '1.0', 'categoryPercentage' => '1.0',
        ]);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/redshifts'));

        // OPTIONAL
         $this->chart->minimalist(false);
         $this->chart->displayLegend(false);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {

        $redshiftCount = calculations::select('redshift_result', DB::raw('COUNT(*) as total'))->groupBy('redshift_result')->orderBy('redshift_result')->get();
        $redshiftArray = $redshiftCount->pluck('total')->all();
        $this->chart->dataset('Redshifts', 'line', $redshiftArray)
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor('rgba(205, 32, 31, 0.4)');

    }
}
