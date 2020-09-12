<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function custom()
	{
		return view('analytics');
	}
}
