<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class methods extends Model
{
    //
    use CrudTrait;

    protected $fillable = [

      'method_id', 'method_name', 'python_script_path', 'created_at', 'updated_at',

    ];

}
