<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	$checkboxes = DB::table('methods')->select('method_id','python_script_path','method_name', 'method_description')->where('removed', 'NO')->get();
        return view('calculation', compact("checkboxes"));
    }

	public function fail(Request $request)
	{
		$msg = $request;
		return(dump($msg));
		$checkboxes = DB::table('methods')->select('method_id','python_script_path','method_name', 'method_description')->where('removed', 'NO')->get();
		return view('calculation', compact("checkboxes"));
	}

}
