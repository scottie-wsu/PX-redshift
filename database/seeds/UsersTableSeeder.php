<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1,10) as $index){
        	User::create([
        		'name' => $faker->name,
				'email' => $faker->email,
				'password' => bcrypt('secret'),
				'institution' => $faker->optional($weight = 0.5, $default = 'wsu')->word,
				'level' => $faker->numberBetween(0,1)
			]);
		}
    }
}
