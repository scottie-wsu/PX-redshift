<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;

class Plot {
    public $name;
    public $desc;
    public $sql;
    public $graphTitle;
    public $graphXLabel;
    public $graphYLabel;

    public function __construct($name_, $desc_, $sql_, $graphTitle_, $graphXLabel_, $graphYLabel_) {
        $this->name = $name_;
        $this->desc = $desc_;
        $this->sql = $sql_;
        $this->graphTitle = $graphTitle_;
        $this->graphXLabel = $graphXLabel_;
        $this->graphYLabel = $graphYLabel_;
    }
}

class PlotController extends Controller
{
    public $availablePlots = array("jobPerUser" => "Job count per user",
    "jobPerInstitution" => "Job count per institution",
    "userPerInstitution" => "User count per institution",
    "calculationPerUser" => "Calculation count per user",
    "calculationPerJob" => "Calculation count per job",
    "calculationPerInstitution" => "Calculation count per institution",
    "calculationPerMethod" => "Calculation count per method"); 
    public $plots = array();
    public $graphTypes = array("bar-chart", "line-chart");

    public function __construct() {
        $this->plots["jobPerUser"] = new Plot("jobPerUser", "Job count per user", "select count(jobs.job_id) as 'val', users.id as 'label' from jobs, users where jobs.user_id = users.id GROUP by users.id", "# of Jobs per User", "User ID", "# of jobs");
        $this->plots["jobPerInstitution"] = new Plot("jobPerInstitution", "Job count per institution", "select count(jobs.job_id) as 'val', users.institution as 'label' from jobs, users where jobs.user_id = users.id GROUP by users.institution", "# of Jobs per Institution", "Institutions", "# of jobs");
        $this->plots["userPerInstitution"] = new Plot("userPerInstitution", "User count per institution", "select count(users.id) as 'val', users.institution as 'label' from users GROUP by users.institution", "# of Users per Institution", "Institutions", "# of users");
        $this->plots["calculationPerUser"] = new Plot("calculationPerUser", "Calculation count per user", "select count(calculations.real_calculation_id) as 'val', users.id as 'label' from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.id", "# of Calculations per User", "User IDs", "# of calculations");
        $this->plots["calculationPerJob"] = new Plot("calculationPerJob", "Calculation count per job", "select count(calculations.real_calculation_id) as 'val', redshifts.job_id as 'label' from redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id GROUP by redshifts.job_id", "# of Calculations per Job", "Jobs IDs", "# of calculations");
        $this->plots["calculationPerInstitution"] = new Plot("calculationPerInstitution", "Calculation count per institution", "select count(calculations.real_calculation_id) as 'val', users.institution as 'label' from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.institution", "# of Calculations per Institution", "Insitutions", "# of calculations");
        $this->plots["calculationPerMethod"] = new Plot("calculationPerMethod", "Calculation count per method", "select count(calculations.real_calculation_id) as 'val', calculations.method_id as 'label' from calculations GROUP by calculations.method_id", "# of Calculations per Method", "Method IDs", "# of calculations");
    }

    public function Index() {
        return View::make('plots')->with('availablePlots', $this->availablePlots);
    }

    public function FetchPlot(Request $request) {
        $selectedPlot = $request->input('selected-plot');
        $selectedPlotType = $request->input('selected-plot-type');
        $result = array();
        if (array_key_exists($selectedPlot, $this->plots) && in_array($selectedPlotType, $this->graphTypes)) {
            $result =  DB::select($this->plots[$selectedPlot]->sql);
        }
        else {
            return View::make('plots')->with('availablePlots', $this->availablePlots)->with('invalidForm', TRUE);
        }

        $labels = array();
        $data = array();
        foreach($result as $x) {
            array_push($labels, $x->label);
            array_push($data, $x->val);
        }

        return View::make('plots')
        ->with('availablePlots', $this->availablePlots)
        ->with('graphType', $selectedPlotType)
        ->with('graphTitle', $this->plots[$selectedPlot]->graphTitle)
        ->with('graphXLabel', $this->plots[$selectedPlot]->graphXLabel)
        ->with('graphYLabel', $this->plots[$selectedPlot]->graphYLabel)
        ->with('data', $data)
        ->with('labels', $labels);
    }
}