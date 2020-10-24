<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('status', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('calculation_id')->unsigned();
			$table->integer('method_id')->unsigned();
			$table->text('status')->nullable();
			$table->text('error_log')->nullable();
			$table->foreign('calculation_id', 'status_ibfk_1')->references('calculation_id')->on('redshifts');
			$table->foreign('method_id', 'status_ibfk_2')->references('method_id')->on('methods');

		});
	}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status');
    }
}
