<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;

class PlotController extends Controller
{
    public $availablePlots = array("jobPerUser" => "Job count per user",
    "jobPerInstitution" => "Job count per institution",
    "userPerInstitution" => "User count per institution",
    "calculationPerUser" => "Calculation count per user",
    "calculationPerJob" => "Calculation count per job",
    "calculationPerInstitution" => "Calculation count per institution",
    "calculationPerMethod" => "Calculation count per method"); 

    public function Index() {
        return View::make('plots')->with('availablePlots', $this->availablePlots);
    }

    public function FetchPlot(Request $request) {
        $selectedPlot = $request->input('selected-plot');
        // switch($selectedPlot) {
        //     case jobCounte
        // }
        $result = array();
        switch($selectedPlot) {
            case "jobPerUser":
                $result = DB::select("select count(jobs.job_id) as 'val', users.id as 'label' from jobs, users where jobs.user_id = users.id GROUP by users.id");
            break;
            case "jobPerInstitution":
                $result = DB::select("select count(jobs.job_id) as 'val', users.institution as 'label' from jobs, users where jobs.user_id = users.id GROUP by users.institution");             
            break;
            case "userPerInstitution":
                $result = DB::select("select count(users.id) as 'val', users.institution as 'label' from users GROUP by users.institution");
            break;
            case "calculationPerUser":
                $result = DB::select("select count(calculations.real_calculation_id) as 'val', users.id as 'label' from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.id");
            break;
            case "calculationPerJob":
                $result = DB::select("select count(calculations.real_calculation_id) as 'val', redshifts.job_id as 'label' from redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id GROUP by redshifts.job_id");
            break;
            case "calculationPerInstitution":
                $result = DB::select("select count(calculations.real_calculation_id) as 'val', users.institution as 'label' from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.institution");
            break;
            case "calculationPerMethod":
                $result = DB::select("select count(calculations.real_calculation_id) as 'val', calculations.method_id as 'label' from calculations GROUP by calculations.method_id");
            break;

        }

        $labels = array();
        $data = array();
        foreach($result as $x) {
            array_push($labels, $x->label);
            array_push($data, $x->val);
        }

        return View::make('plots')
        ->with('availablePlots', $this->availablePlots)
        ->with('title', $this->availablePlots[$selectedPlot])
        ->with('data', $data)
        ->with('labels', $labels);
    }
}

// $jobCountPerUser = DB::select("select count(jobs.job_id) as 'job count', users.id from jobs, users where jobs.user_id = users.id GROUP by users.id");
// $jobCountPerInstitution = DB::select("select count(jobs.job_id) as 'job count', users.institution from jobs, users where jobs.user_id = users.id GROUP by users.institution");
// $userCountPerInstitution = DB::select("select count(users.id) as 'users_count', users.institution from users GROUP by users.institution");
// $calculationCountPerUser = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', users.id from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.id");
// $calculationCountPerJob = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', redshifts.job_id from redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id GROUP by redshifts.job_id");
// $calculationCountPerInstitution = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', users.institution from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.institution");
// $calculationCountPerMethod = DB::select("select count(calculations.real_calculation_id) as 'calculations_count', calculations.method_id from calculations GROUP by calculations.method_id");