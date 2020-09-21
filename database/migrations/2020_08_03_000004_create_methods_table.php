<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('methods', function (Blueprint $table) {
            $table->increments('method_id');
            $table->string('method_name');
            $table->text('python_script_path');
            $table->text('method_description')->nullable();
            $table->text('removed')->nullable();
            $table->timestamps();
        });
        DB::table('methods')->insert(array(
            [
                'method_name' => 'mean',
                'python_script_path' => 'scripts/mean.py',
                'method_description' => 'Average of all measurements',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'method_name' => 'sum',
                'python_script_path' => 'scripts/sum.py',
                'method_description' => 'Sum of all measurements',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'method_name' => 'minus',
                'python_script_path' => 'scripts/minus.py',
                'method_description' => 'subtracting all measurements',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('methods');
    }
}
