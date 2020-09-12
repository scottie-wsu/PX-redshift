<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\calculations;
use App\User;
use App\redshifts;
use Illuminate\Support\Facades\DB;
/**
 * Class JobsChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class JobsChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        $institution = User::orderBy('created_at')->pluck('id', 'name', 'institution');

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels($institution->keys());

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/jobs'));

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

        //can use basically raw sql in just the select command as we are not reading in any input from the user, so all data being entered into the query has already been vetted
        $jobCountPerUser = DB::select('SELECT name, COUNT(*) as total FROM users INNER JOIN redshifts on users.id = redshifts.user_id GROUP BY redshifts.user_id');
        $this->chart->dataset('Jobs completed', 'bar', collect($jobCountPerUser)->pluck('total')->toArray())
            ->color('rgba(31, 31, 255, 1)')
            ->backgroundColor('rgba(31, 31, 255, 0.4)')
            ->options([
                'barPercentage' => '0.0'
            ]);

     }
}



