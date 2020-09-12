<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

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
        $attribute_name = "python_script_path";
        $disk = "public";
        $destination_path = "/scripts";
        $this->attributes[$attribute_name] = $value;
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

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
