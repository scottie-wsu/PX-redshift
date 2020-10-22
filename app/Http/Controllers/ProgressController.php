<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function Index() {
        return(view('progressnew'));
    }
    
    public function FetchProgress() {
        header("Content-type: application/json; charset=utf-8");
        $submitted = DB::select("SELECT count(redshifts.calculation_id) as 'count' from redshifts, jobs, users where redshifts.status  = 'SUBMITTED' and redshifts.job_id = jobs.job_id and jobs.user_id = users.id and users.id = ?", [auth()->id()]);
        $processing = DB::select("SELECT count(redshifts.calculation_id) as 'count' from redshifts, jobs, users where redshifts.status  = 'PROCESSING' and redshifts.job_id = jobs.job_id and jobs.user_id = users.id and users.id = ?", [auth()->id()]);
        $completed = DB::select("SELECT count(redshifts.calculation_id) as 'count' from redshifts, jobs, users where redshifts.status = 'COMPLETED' and redshifts.job_id = jobs.job_id and jobs.user_id = users.id and users.id = ?", [auth()->id()]);

        $data = array("submitted" => $submitted[0]->count, "completed" => $completed[0]->count, "processing" => $processing[0]->count);

        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
