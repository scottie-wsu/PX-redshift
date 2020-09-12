<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class guestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$calculate->assigned_calc_ID = $request->input('assigned_calc_ID');
    return view('guest');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        	
    	$str =  $request->input('optical_u') . " " . $request->input('optical_g') . " " . $request->input('optical_r')  . " " . $request->input('optical_i') . " " . $request->input('optical_z') .  " " . $request->input('infrared_three_six') . " " . $request->input('infrared_four_five') . " " . $request->input('infrared_five_eight') . " " . $request->input('infrared_eight_zero') . " " . $request->input('infrared_J') . " " . $request->input('infrared_K')  . " " .  $request->input('radio_one_four');
   		$str = escapeshellcmd($str);
    	$process = new Process('python sum.py ' . $str);
    	try {
  			  $process->mustRun();
              $calculate = $process->getOutput();
		} catch (ProcessFailedException $exception) {
   			echo $exception->getMessage();
            $calculate= -1;
        }
        
     $red_result=(int)$calculate;
        // return redirect('/calculation')->with(['result', $calculate]);
        // session()->flash('red_result', $red_result);
       // return redirect('/history')->with(compact('red_result'));
         return view('result',compact('red_result'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
