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

        'method_name', 'python_script_path', 'method_description', 'created_at', 'updated_at',

    ];


    public function setPythonScriptPathAttribute($value)
    {
    	$request = \Request::instance();
        $attribute_name = "python_script_path";

        //test logic, can remove
		if($request->hasFile($attribute_name)){
			if($request->file($attribute_name)->isValid()){
				$new_file_name = $request->file($attribute_name)->getClientOriginalName();
				\Storage::disk('public')->put('originalName', $new_file_name);
			}
		}

		//
		// BEGIN VERSIONING LOGIC
		//
		$methodId = $request->method_id;
		$query = Methods::select('*')->where('method_id', $methodId)->first();
		$queryPath = $query->python_script_path;
		$queryPathExplode = explode("/", $queryPath);
		//might be a better way of doing this with php pathinfo
		$oldPath = explode(".py", $queryPathExplode[1]);
		$time = Carbon::now();
		$timeNoSpace = str_replace(" ", "_", $time);
		$timeFixed = str_replace(":", "", $timeNoSpace);

		$foundFlag = 0;
		$dirs = \Storage::disk('public')->directories("scripts");
		foreach($dirs as $dir){
			$explodeDir = explode("-", $dir);
			$methodIdFolder = "scripts/".$methodId;
			if($explodeDir[0] == $methodIdFolder){
				$newLocation = "$dir/$timeFixed-$queryPathExplode[1]";
				\Storage::disk('public')->copy($queryPath,$newLocation);
				$foundFlag = 1;
			}
		}

		if($foundFlag == 0){
			$newLocation = "scripts/$methodId-$oldPath[0]_archive/$timeFixed-$queryPathExplode[1]";
			\Storage::disk('public')->copy($queryPath,$newLocation);
		}

		//
		//END VERSIONING LOGIC
		//


		$disk = "public";
        $destination_path = "/scripts";
        $this->attributes[$attribute_name] = $value;
		//$fileName = $this->attributes[$attribute_name];
		//$this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);


		// if a new file is uploaded, delete the file from the disk
		if ($request->hasFile($attribute_name) &&
			$this->{$attribute_name} &&
			$this->{$attribute_name} != null) {
			\Storage::disk('public')->append('originalName.yy', '1');
			\Storage::disk('public')->append('originalName.yy', $this->{$attribute_name});

			\Storage::disk($disk)->delete($this->{$attribute_name});
			$this->attributes[$attribute_name] = null;
			\Storage::disk('public')->append('originalName.yy', '2');

			\Storage::disk('public')->append('originalName.yy', $this->{$attribute_name});
			\Storage::disk('public')->append('originalName.yy', '3');
			\Storage::disk('public')->append('originalName.yy', $this->attributes[$attribute_name]);

		}

		//// if the file input is empty, delete the file from the disk
		//if (is_null($value) && $this->{$attribute_name} != null) {
			//\Storage::disk($disk)->delete($this->{$attribute_name});
			//$this->attributes[$attribute_name] = null;
		//}

		// if a new file is uploaded, store it on disk and its filename in the database
		if ($request->hasFile($attribute_name) && $request->file($attribute_name)->isValid()) {
			// 1. Generate a new file name
			$file = $request->file($attribute_name);
			$new_file_name = $file->getClientOriginalName();

			// 2. Move the new file to the correct path
			$file_path = $file->storeAs($destination_path, $new_file_name, $disk);

			// 3. Save the complete path to the database
			$this->attributes[$attribute_name] = $file_path;
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
