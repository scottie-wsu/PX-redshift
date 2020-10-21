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
    public $plots = array();
    public $graphTypes = array("bar-chart" => "Bar Chart", "line-chart" => "Line Chart", "pie-chart" => "Pie Chart");

    public function __construct() {
        $this->plots["jobPerUser"] = new Plot("jobPerUser", "Number of Jobs per User", "select count(jobs.job_id) as 'val', users.id as 'label' from jobs, users where jobs.user_id = users.id GROUP by users.email", "Number of Jobs per User", "User ID", "Number of Jobs");
        $this->plots["jobPerInstitution"] = new Plot("jobPerInstitution", "Number of Jobs per Institution", "select count(jobs.job_id) as 'val', users.institution as 'label' from jobs, users where jobs.user_id = users.id GROUP by users.institution", "Number of Jobs per Institution", "Institutions", "Number of Jobs");
        $this->plots["userPerInstitution"] = new Plot("userPerInstitution", "Number of Users per Institution", "select count(users.id) as 'val', users.institution as 'label' from users GROUP by users.institution", "Number of Users per Institution", "Institutions", "Number of Users");
        $this->plots["calculationPerUser"] = new Plot("calculationPerUser", "Number of Calculations per User", "select count(calculations.real_calculation_id) as 'val', users.id as 'label' from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.id", "Number of Calculations per User", "User IDs", "Number of Calculations");
        $this->plots["calculationPerJob"] = new Plot("calculationPerJob", "Number of Calculations per Job", "select count(calculations.real_calculation_id) as 'val', redshifts.job_id as 'label' from redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id GROUP by redshifts.job_id", "Number of Calculations per Job", "Jobs IDs", "Number of Calculations");
        $this->plots["calculationPerInstitution"] = new Plot("calculationPerInstitution", "Number of Calculations per Institution", "select count(calculations.real_calculation_id) as 'val', users.institution as 'label' from users, jobs, redshifts, calculations where calculations.galaxy_id = redshifts.calculation_id AND redshifts.job_id = jobs.job_id AND jobs.user_id = users.id GROUP by users.institution", "Number of Calculations per Institution", "Insitutions", "Number of Calculations");
        $this->plots["calculationPerMethod"] = new Plot("calculationPerMethod", "Number of Calculations Per Method", "select count(calculations.real_calculation_id) as 'val', calculations.method_id as 'label' from calculations GROUP by calculations.method_id", "Number of Calculations per Method", "Method IDs", "Number of Calculations");
    }

    public function Index() {
        return View::make('plotting')->with('availablePlots', $this->plots)->with('graphTypes', $this->graphTypes);
    }

    public function FetchPlot(Request $request) {
        $selectedPlot = $request->input('selected-plot');
        $selectedPlotType = $request->input('selected-plot-type');
        $result = array();
        if (array_key_exists($selectedPlot, $this->plots) && array_key_exists($selectedPlotType, $this->graphTypes)) {
            $result =  DB::select($this->plots[$selectedPlot]->sql);
        }
        else {
            return View::make('plotting')->with('availablePlots', $this->plots)->with('invalidForm', TRUE)->with('graphTypes', $this->graphTypes);
        }

        $labels = array();
        $data = array();
        foreach($result as $x) {
            array_push($labels, $x->label);
            array_push($data, $x->val);
        }

        return View::make('plotting')
        ->with('graphTypes', $this->graphTypes)
        ->with('selectedPlot', $this->plots[$selectedPlot]->name)
        ->with('selectedGraph', $selectedPlotType)
        ->with('availablePlots', $this->plots)
        ->with('graphType', $selectedPlotType)
        ->with('graphTitle', $this->plots[$selectedPlot]->graphTitle)
        ->with('graphXLabel', $this->plots[$selectedPlot]->graphXLabel)
        ->with('graphYLabel', $this->plots[$selectedPlot]->graphYLabel)
        ->with('data', $data)
        ->with('labels', $labels);
    }
}