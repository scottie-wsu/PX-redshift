<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
			$table->increments('job_id');
			$table->text('job_name');
			$table->text('job_description')->nullable();
			$table->integer('user_id')->unsigned();
			$table->timestamps();
			$table->foreign('user_id', 'jobs_ibfk_1')->references('id')->on('users');

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
