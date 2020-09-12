<?php

namespace App\Http\Controllers;

use App\redshifts;
use App\calculations;
use App\methods;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use App\Exports\RedshiftExport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use function MongoDB\BSON\toJSON;
use Illuminate\Support\Facades\Mail;
use App\Mail\CalcCompleteMail;



class CalculationController extends Controller
{

  public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

        return view('calculation');
    }

    public function export(){
    	$str =  'redshift_result' . date('Y-m-d_h:m:s',time()) . '.csv';
        return Excel::download(new RedshiftExport, $str);
	}


    public function import(Request $request){

      	$target_dir = "temp/";
	 	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	  	$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
 		$file = fopen($target_file, "r");
 		$lineCount = count(file($target_file));
		//$str = array();
        $galaxy =  array();
        $galaxy_ID = array();
        $i = 0;
        $lineCounter = 0;

        while($lineCounter < $lineCount){
            $data = fgetcsv($file, $delimiter= ",");
            if(is_numeric($data[0])){ //sizeof($data) > 14 &&
                $galaxy[$i] = new redshifts();
                //should we be assigning assigned_calc_id if it's a primary key?
                $galaxy[$i]->assigned_calc_ID = $data[0];
                $galaxy[$i]->optical_u = floatval($data[1]);
                $galaxy[$i]->optical_v = floatval($data[2]);
                $galaxy[$i]->optical_g = floatval($data[3]);
                $galaxy[$i]->optical_r = floatval($data[4]);
                $galaxy[$i]->optical_i = floatval($data[5]);
                $galaxy[$i]->optical_z =  floatval($data[6]);
                $galaxy[$i]->infrared_three_six = floatval($data[7]);
                $galaxy[$i]->infrared_four_five =  floatval($data[8]);
                $galaxy[$i]->infrared_five_eight =  floatval($data[9]);
                $galaxy[$i]->infrared_eight_zero =  floatval($data[10]);
                $galaxy[$i]->infrared_J =  floatval($data[11]);
                $galaxy[$i]->infrared_H =  floatval($data[12]);
                $galaxy[$i]->infrared_K =  floatval($data[13]);
                $galaxy[$i]->radio_one_four =  floatval($data[14]);
                $galaxy[$i]->user_ID = auth()->id();
                //$galaxy[$counter]->save();
                $galaxy_ID[$i] = DB::getPdo()->lastInsertId();
                //
                $galaxy[$i]->method_id = 1;//(int)$request->input('methods');
                $galaxy[$i]->toJson();
                $i++;
            }
            $lineCounter++;
            //dump($galaxy);
  		}
		fclose($file);


        //setting up all required API data to send via JSON
        $dataJSON = $galaxy;
        //add method ID so API knows what method to use on the data
        //$dataJSON = $dataAPI->toJSON();
        ////initialising the guzzle client
        $urlAPI = 'http://127.0.0.1:5000';
        $client = new Client(['base_uri' => $urlAPI]);
        ////writing the code to send data to the API
        $client->request('POST', '/', ['json' => $dataJSON]);

        //$calculate = array();
        //$method = methods::select('python_script_path')->where('method_id', $request->input('method_id_for_files'))->get();
        //$method = collect($method)->pluck('python_script_path')->toArray();

    	//for($i = 0; $i < $counter; $i++){
    			//$process = new Process('python ' . $method[0]. ' ' . $str[$i]);
        		//$calculate[$i] = new calculations();

       			//try {
              		//$process->mustRun();
             		//$calculate[$i]->redshift_result = $process->getOutput();
                	//$calculate[$i]->galaxy_id = $galaxy_ID[$i];
    				//$calculate[$i]->method_id = $request->input('method_id_for_files');
        		//} catch (ProcessFailedException $exception) {
           			 //$calculate[$i]->redshift_result = -100;
        		//}

        //}
		//for($i = $counter - 1; $i >= 0; $i--){
			//$calculate[$i]->save();
        //}
        return redirect('/history');
    }

	public function home(){
       $pages=20;

        if (request()->has('galaxy_id')){
                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.assigned_calc_ID')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('optical_u')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.optical_u')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('optical_v')){

            $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
                ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.optical_u')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('optical_g')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.optical_g')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('optical_r')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.optical_r')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('optical_i')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.optical_i')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('optical_z')){

                                         $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.optical_z')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_three_six')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_three_six')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_four_five')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_four_five')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_five_eight')){

                                         $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_five_eight')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_eight_zero')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_eight_zero')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_J')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_J')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_H')){

            $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
                ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_J')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('infrared_K')){

                                        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.infrared_K')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('radio_1.4')){

            $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('redshifts.radio_one_four')->where('redshifts.user_ID', auth()->id())->paginate($pages);
        }
        else if (request()->has('redshift_result')){
            //   $calculations= calculations::join('redshifts','redshifts.calculation_ID','=','calculations.galaxy_ID')->where('redshifts.user_ID', auth()->id())->paginate($pages);

             $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('calculations.redshift_result')->where('redshifts.user_ID', auth()->id())->paginate($pages);



        }
        else{



        $calculations= calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
            ->select('redshifts.*','calculations.redshift_result')->orderByDesc('calculations.updated_at')->where('redshifts.user_ID', auth()->id())->paginate($pages);

                                    }

        return view('history', compact('calculations'));
    }

    public function search(Request $req)
    {
        //
        $pages=20;
        $q = $req->input('q');
    $user = calculations::join('redshifts', 'calculation_ID', '=', 'calculations.galaxy_ID')
     ->where('redshifts.user_ID', auth()->id())->where(function ($query) use ($q)  {
    $query->orWhere('assigned_calc_ID','LIKE','%'.$q.'%')
    ->orWhere('redshifts.optical_u','LIKE','%'.$q.'%')->
    orWhere('redshifts.optical_v','LIKE','%'.$q.'%')->
    orWhere('optical_g','LIKE','%'.$q.'%')->
    orWhere('redshifts.optical_r','LIKE','%'.$q.'%')->
    orWhere('redshifts.optical_i','LIKE','%'.$q.'%')->
    orWhere('redshifts.optical_z','LIKE','%'.$q.'%')->
    orWhere('redshifts.infrared_three_six','LIKE','%'.$q.'%')->
    orWhere('redshifts.infrared_five_eight','LIKE','%'.$q.'%')->
    orWhere('redshifts.infrared_eight_zero','LIKE','%'.$q.'%')->
    orWhere('redshifts.infrared_J','LIKE','%'.$q.'%')->
    orWhere('redshifts.infrared_H','LIKE','%'.$q.'%')->
    orWhere('redshifts.infrared_K','LIKE','%'.$q.'%')->
    orWhere('redshifts.radio_one_four','LIKE','%'.$q.'%')->
    orWhere('calculations.redshift_result','LIKE','%'.$q.'%');
})->paginate($pages);

    if(count($user) > 0){
        $details=1;
        return view('search',compact('user','details'));
    }
    else {

        return view ('search')->withMessage('No Details found. Try to search again!');}
    }

    public function store(Request $request){

        $galaxy = new redshifts();
    	$calculate = new calculations();
		$galaxy->assigned_calc_ID = $request->input('assigned_calc_ID');
    	$galaxy->optical_u = $request->input('optical_u');
    	$galaxy->optical_v = $request->input('optical_v');
    	$galaxy->optical_g = $request->input('optical_g');
    	$galaxy->optical_r = $request->input('optical_r');
    	$galaxy->optical_i = $request->input('optical_i');
    	$galaxy->optical_z = $request->input('optical_z');
    	$galaxy->infrared_three_six = $request->input('infrared_three_six');
    	$galaxy->infrared_four_five = $request->input('infrared_four_five');
    	$galaxy->infrared_five_eight = $request->input('infrared_five_eight');
    	$galaxy->infrared_eight_zero = $request->input('infrared_eight_zero');
    	$galaxy->infrared_J = $request->input('infrared_J');
    	$galaxy->infrared_H = $request->input('infrared_H');
    	$galaxy->infrared_K = $request->input('infrared_K');
        $galaxy->radio_one_four = $request->input('radio_one_four');
    	$galaxy->user_ID = auth()->id();
    	// optical g + optical u
    	//$str =  $galaxy->optical_u . " " . $galaxy->optical_g . " " . $galaxy->optical_r  . " " . $galaxy->optical_i . " " . $galaxy->optical_z .  " " . $galaxy->infrared_three_six . " " . $galaxy->infrared_four_five . " " . $galaxy->infrared_five_eight . " " . $galaxy->infrared_eight_zero . " " . $galaxy->infrared_J . " " . $galaxy->infrared_K  . " " .  $galaxy->radio_one_four;
   		//adds a / to command characters
    	//$str = escapeshellcmd($str);



        $galaxy->save();
   		$calculate->galaxy_id = DB::getPdo()->lastInsertId();

        $calculate->method_id = (int)$request->input('methods');

        //using this reset command as the code below isn't compatible with an array of methods,
        //and there's no point fixing it when we won't be using it once the API is up and running
        //also for some reason putting calculate->methodid[0] gave errors about array to str
        $reset = $calculate->method_id[0];

        $method = methods::select('python_script_path')->where('method_id', $reset)->get();
        $method = collect($method)->pluck('python_script_path')->toArray();

        //$process = new Process(['c:\Python27\python27.exe', $method[0], $galaxy->optical_u, $galaxy->optical_v,
            //$galaxy->optical_g, $galaxy->optical_r, $galaxy->optical_i, $galaxy->optical_z, $galaxy->infrared_three_six,
            //$galaxy->infrared_four_five, $galaxy->infrared_five_eight, $galaxy->infrared_eight_zero,
            //$galaxy->infrared_J, $galaxy->infrared_H, $galaxy->infrared_K, $galaxy->radio_one_four]);


    	//try {
    	    //$process->mustRun();
            //$calculate->redshift_result = 123123; //$process->getOutput();
            //$calculate->redshift_result = rtrim($calculate->redshift_result);
		//} catch (ProcessFailedException $exception) {
    	    //$calculate->redshift_result = -150;
        //}
        //$calculate->save();

        //setting up all required API data to send via JSON
        $dataAPI = clone $galaxy;
        //add method ID so API knows what method to use on the data
        $dataAPI->method_id = $calculate->method_id;
        $dataJSON = $dataAPI->toJSON();
        ////initialising the guzzle client
        $urlAPI = 'http://127.0.0.1:5000';
        $client = new Client(['base_uri' => $urlAPI]);
        ////writing the code to send data to the API
        $client->request('POST', '/', ['json' => $dataJSON]);

     	$red_result=$calculate->redshift_result;
        return redirect('/history')->with(compact('red_result'));


    }
}
?>
