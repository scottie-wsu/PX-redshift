<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class calculations extends Model
{
    //
   	 protected $primaryKey = 'calculation_id';
     protected $fillable = [

      'galaxy_id', 'method_id' ,'real_calculation_id', 'redshift_result',

    ];
}
