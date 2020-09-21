<?php

namespace App\Http\Controllers;

use App\redshifts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use GuzzleHttp\Client;
use App\User;


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
    	$galaxy = array();

		$galaxy[0] = new redshifts();
		$galaxy[0]->assigned_calc_ID = $request->input('assigned_calc_ID');
		$galaxy[0]->optical_u = $request->input('optical_u');
		$galaxy[0]->optical_v = $request->input('optical_v');
		$galaxy[0]->optical_g = $request->input('optical_g');
		$galaxy[0]->optical_r = $request->input('optical_r');
		$galaxy[0]->optical_i = $request->input('optical_i');
		$galaxy[0]->optical_z = $request->input('optical_z');
		$galaxy[0]->infrared_three_six = $request->input('infrared_three_six');
		$galaxy[0]->infrared_four_five = $request->input('infrared_four_five');
		$galaxy[0]->infrared_five_eight = $request->input('infrared_five_eight');
		$galaxy[0]->infrared_eight_zero = $request->input('infrared_eight_zero');
		$galaxy[0]->infrared_J = $request->input('infrared_J');
		$galaxy[0]->infrared_H = $request->input('infrared_H');
		$galaxy[0]->infrared_K = $request->input('infrared_K');
		$galaxy[0]->radio_one_four = $request->input('radio_one_four');
		$galaxy[0]->toJson();

		$galaxy[1] = new redshifts();
		$galaxy[1]->methods = $request->input('methods');
		//todo - this is reliant on guest being id 1 in the users table.
		$galaxy[1]->user_ID = 1;

		//todo - this is reliant on guest being id 1 in the users table.
		$userEmail = User::select('email')->where('id', 1)->first();
		$mergeData = $userEmail . " : " . random_bytes(32);
		$cipherMethod = "aes-128-cbc";
		$key = "5rCBIs9Km!!cacr1";
		$iv = "123hasdba036vpax";
		$tokenData = openssl_encrypt($mergeData, $cipherMethod, $key, $options=0, $iv);
		$galaxy[1]->token = $tokenData;
		$galaxy[1]->toJson();
		//setting up all required API data to send via JSON
		$dataJSON = $galaxy;
		////initialising the guzzle client
		$urlAPI = 'http://127.0.0.1:5000';
		$client = new Client(['base_uri' => $urlAPI]);
		////writing the code to send data to the API
		$client->request('POST', '/', ['json' => $dataJSON]);


		//todo - implement waiter page for guest too, then the link on that page when the calc
		//todo - completes is just the result page (w/ updates) with the result
		$red_result = 1;
		return(dump($red_result));
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
