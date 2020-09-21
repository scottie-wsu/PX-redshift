<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedshiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redshifts', function (Blueprint $table) {
            $table->increments('calculation_id');
            $table->string('assigned_calc_id');
            $table->double('optical_u',16,8);
            $table->double('optical_v',16,8);
            $table->double('optical_g', 16, 8);
            $table->double('optical_r', 16, 8);
            $table->double('optical_i', 16, 8);
            $table->double('optical_z', 16, 8);
            $table->double('infrared_three_six', 16, 8);
            $table->double('infrared_four_five', 16, 8);
            $table->double('infrared_five_eight', 16, 8);
            $table->double('infrared_eight_zero', 16, 8);
            $table->double('infrared_J', 16, 8);
            $table->double('infrared_H', 16, 8);
            $table->double('infrared_K', 16, 8);
            $table->double('radio_one_four', 16, 8);
            $table->text('status');
            $table->integer('job_id')->unsigned();
			//$table->integer('user_id')->unsigned()->default(1);
            $table->timestamps();
            //$table->foreign('user_id', 'redshifts_ibfk_1')->references('user_id')->on('jobs');
			$table->foreign('job_id', 'redshifts_ibfk_1')->references('job_id')->on('jobs');

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redshifts');
    }
}
