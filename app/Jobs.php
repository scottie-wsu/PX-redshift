<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'job_id';
	protected $fillable = [

		'job_name', 'job_description', 'user_id', 'created_at', 'updated_at',

	];

}
