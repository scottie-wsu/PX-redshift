<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculations', function (Blueprint $table) { /*->primary on real calc id */
            $table->increments('real_calculation_id');
            $table->integer('galaxy_id')->unsigned();
            $table->integer('method_id')->unsigned();
            $table->double('redshift_result', 16,8);
            $table->timestamps();
            $table->foreign('galaxy_id', 'calculations_ibfk_1')->references('calculation_id')->on('redshifts');
            $table->foreign('method_id', 'calculations_ibfk_2')->references('method_id')->on('methods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculations');
    }
}
