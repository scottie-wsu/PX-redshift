<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Methods extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'methods';
    protected $primaryKey = 'method_id';
    protected $fillable = [

        'method_name', 'python_script_path', 'method_description', 'created_at', 'updated_at', 'removed',

    ];


    public function setPythonScriptPathAttribute($value)
    {
    	$request = \Request::instance();
        $attribute_name = "python_script_path";
		$disk = "public";
		$destination_path = "/scripts";

        if(isset($request->method_id)) {

			//
			// BEGIN VERSIONING LOGIC
			//
			//writes request method id into variable
			$methodId = $request->method_id;
			//pulls database row for the method id selected
			$query = Methods::select('*')->where('method_id', $methodId)->first();
			$queryPath = $query->python_script_path;
			$queryPathExplode = explode("/", $queryPath);
			//might be a better way of doing this with php pathinfo
			$oldPath = explode(".py", $queryPathExplode[1]);
			$time = Carbon::now();
			$timeNoSpace = str_replace(" ", "_", $time);
			$timeFixed = str_replace(":", "", $timeNoSpace);

			$foundFlag = 0;
			//looping over every directory (archive folder) inside the scripts folder
			$dirs = \Storage::disk('public')->directories("scripts");
			foreach ($dirs as $dir) {
				$explodeDir = explode("-", $dir);
				$methodIdFolder = "scripts/" . $methodId;
				//if scripts/{methodId} == scripts/{methodId}
				if ($explodeDir[0] == $methodIdFolder) {
					//creating the new file, which will be scripts/{method-id}-{originalmethodname}_archive/datetime-{latestfilename}.py
					$newLocation = "$dir/$timeFixed-$queryPathExplode[1]";
					\Storage::disk('public')->copy($queryPath, $newLocation);
					$foundFlag = 1;
				}
			}

			//only does logic inside this if, if it loops through all dirs and finds no dir matching the method id of the file
			if ($foundFlag == 0) {
				$newLocation = "scripts/$methodId-$oldPath[0]_archive/$timeFixed-$queryPathExplode[1]";
				\Storage::disk('public')->copy($queryPath, $newLocation);
			}

			//
			//END VERSIONING LOGIC
			//



			$this->attributes[$attribute_name] = $value;
			//\Storage::disk($disk)->append('debug.log', $this->{$attribute_name});
			//\Storage::disk($disk)->append('debug.log', $request->file($attribute_name)->getClientOriginalName());


			// if a new file is uploaded, delete the file from the disk
			if ($request->hasFile($attribute_name) &&
				$this->{$attribute_name} &&
				$this->{$attribute_name} != null) {

				$oldFile = Methods::select('python_script_path')->where('method_id', $request->method_id)->first();
				\Storage::disk($disk)->delete($oldFile->python_script_path);
				//$this->attributes[$attribute_name] = null;


			}


			// if a new file is uploaded, store it on disk and its filename in the database
			if ($request->hasFile($attribute_name) && $request->file($attribute_name)->isValid()) {
				// 1. Generate a new file name
				$file = $request->file($attribute_name);

				$new_file_name = $file->getClientOriginalName();

				// 2. Move the new file to the correct path
				$file_path = $file->storeAs($destination_path, $new_file_name, $disk);

				// 3. Save the complete path to the database
				\Storage::disk($disk)->append('debug.log', $oldFile);

				$this->attributes[$attribute_name] = $file_path;
				$this->attributes['removed'] = 0;
			}

		}//end if isset(request->method_id)
		else{
			// if a new file is uploaded, store it on disk and its filename in the database
			if ($request->hasFile($attribute_name) && $request->file($attribute_name)->isValid()) {
				// 1. Generate a new file name
				$file = $request->file($attribute_name);

				$new_file_name = $file->getClientOriginalName();

				// 2. Move the new file to the correct path
				$file_path = $file->storeAs($destination_path, $new_file_name, $disk);

				// 3. Save the complete path to the database
				$this->attributes[$attribute_name] = $file_path;
				$this->attributes['removed'] = 0;

			}
		}


		//return dump($this->attributes->$attribute_name); // uncomment if this is a translatable field
    }

    //this deletes the file upon deletion of the record in the interface
    public static function boot()
    {
    parent::boot();
    static::deleting(function($obj) {
    \Storage::disk('public')->delete($obj->python_script_path);
    });
    }


}
