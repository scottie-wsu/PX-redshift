<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CalcStatusController extends Controller
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
    
    public function index() {
        return view('status');
    }

    public function process() {
        session_start();
        /*
        *Make JSON out of the arguments from the form   [DONE]
        *Post it to the API                             [DONE]
        *Get the list of calc_id, to check status for   [DONE]
        *Store all the calc_id in the session variable  [DONE]
        */

        //Guzzle client, will use to send HTTP request to the API
        $client = new Client();

        //Make an assosciative array of form values
        $form_input = array(array("assigned_calc_ID" => 007, "optical_u" => (double)request('optical_u'), "optical_v" => (double)request('optical_v'), "optical_g" => (double)request('optical_g'), "optical_r" => (double)request('optical_r'), "optical_i" => (double)request('optical_i'),
        "optical_z" => (double)request('optical_z'), "infrared_three_six" => (double)request('infrared_three_six'), "infrared_four_five" => (double)request('infrared_four_five'), "infrared_five_eight" => (double)request('infrared_five_eight'), "infrared_eight_zero" => (double)request('infrared_eight_zero'),
        "infrared_J" => (double)request('infrared_J'), "infrared_H" => (double)request('infrared_H'), "infrared_K" => (double)request('infrared_K'), "radio_one_four" => (double)request('radio_one_four'), "user_ID" => 2));

        //Methods to process the data upon, and authentication token
        //Note: For testing purposes these values are assigned manually
        array_push($form_input, array("methods" => array(4), "token" => "CLGGUXcQzKyzMFmbANgIw65fUW46chS89x7A9ybJ6/BIJxasxf4aPsddUhWFNKwG5QEEV9gNRHaM8A78lcWVPA==", "job_id" => 49));
        
        //Encode the assosciative array into JSON
        $encoded_ = json_encode($form_input, JSON_PRETTY_PRINT);

        //Make a POST request to the API
        //Note: some of the arguments to the post function are subject to change
        $response_ = $client->post('localhost:8056/php7www/redshift-api/guest', [
            'headers'         => ['Content-Type' => 'application/json'],
            'body'            => $encoded_,
            'allow_redirects' => false,
            'timeout'         => 30
        ]);

        //If the reponse have body
        if ($response_->getBody()) {
            //Store the returned array in the session variable
            $_SESSION['galaxyID'] = json_decode($response_->getBody());
        }

        //Note: just for debugging purposes
        // echo "<pre>";
        // echo $encoded_;
        // echo "</pre>";        

        //Sleep for 5 seconds
        //sleep(5);
        return view('status');
    }  
    
    
    public function fetchStatus() {
        session_start();
        $FINISHED_FLAG = "FINISHED";

        if (isset($_SESSION['galaxyID'])) {
            $total_count = count($_SESSION['galaxyID']);

            $whereClause = "";
            for ($i = 0; $i < $n - 1; $i++) {
                $whereClause .= "galaxy_id = {$_SESSION['galaxyID'][$i]}" . " || ";
            }
            $whereClause .= "galaxy_id = {$_SESSION['galaxyID'][$n - 1]}";

            $processed_count = DB::table('redshifts')->whereRaw($whereClause)->count();
            
            //If processed_count == total_count, means all the requested calculations have been processed.
            if ($processed_count == $total_count) {
                unset($_SESSION['galaxyID']);
                echo $FINISHED_FLAG;
                return;
            }
            
            //Create and return the JSON response
            $processed_precent = $processed_count / $total_count * 100;
            $response_ = array("progress" => "{$processed_precent}" . "%", "current" => $processed_count, "total" => $total_count);
            echo json_encode($response_);
        }
        else {
            echo $FINISHED_FLAG;
        }
    }
}
