<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class redshifts extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use CrudTrait;

    protected $table = 'redshifts';
    protected $primaryKey = 'calculation_id';
    protected $fillable = [

        'assigned_calc_id', 'optical_u', 'optical_v', 'optical_g', 'optical_r', 'optical_i', 'optical_z',
		'infrared_three_six', 'infrared_four_five', 'infrared_five_eight', 'infrared_eight_zero',
		'infrared_J', 'infrared_H' ,'infrared_K', 'radio_one_four', 'status', 'job_id'

    ];
}
