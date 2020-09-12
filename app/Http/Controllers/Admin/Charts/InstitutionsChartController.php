<?php

namespace App\Http\Controllers\Admin\Charts;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\User;

/**
 * Class InstitutionsChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InstitutionsChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $institution = User::orderBy('created_at')->pluck('id', 'institution');

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels($institution->keys());

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/institutions'));


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
        //go through user table and count number of distinct institutions
        $institutionCount = User::select('institution', DB::raw('count(*) as total'))->groupBy('institution')->get();

          $this->chart->dataset('Users', 'bar', $institutionCount->pluck('total'))
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor('rgba(205, 32, 31, 0.4)');

        $jobCountPerInstitution = DB::select('SELECT institution, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY users.institution');
        $this->chart->dataset('Jobs completed', 'bar', collect($jobCountPerInstitution)->pluck('total')->toArray())
            ->color('rgba(31, 31, 255, 1)')
            ->backgroundColor('rgba(31, 31, 255, 0.4)');

     }


}

