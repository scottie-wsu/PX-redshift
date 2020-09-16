<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
use App\Jobs;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
		$faker = Faker::create();
		$users = User::all()->pluck('id')->toArray();

		foreach(range(1,48) as $index){
			Jobs::create([
				'job_name' => $faker->word,
				'job_description' => $faker->sentence,
				'user_id' => $faker->randomElement($users)
			]);
		}
    }
}
