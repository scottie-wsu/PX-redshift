<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('institution');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('level')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
		DB::table('users')->insert(array(
			[
				'name' => 'test',
				'email' => 'test@test.com',
				'institution' => 'TestAccount',
				'password' => '$2y$10$hxFJFPQMyt7/dma1bVxAtu6o9JINP78VCQdbfXS3MU9I391lJ3BYe',
				'level' => 1,
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
        Schema::dropIfExists('users');
    }
}
