<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\calculations;
use App\redshifts;
use App\methods;

class calculationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
		$faker = Faker::create();
		$redshifts = redshifts::all()->pluck('calculation_id')->toArray();
		$methods = methods::all()->pluck('method_id')->toArray();

		foreach(range(1,500) as $index){
			calculations::create([
				"galaxy_id" => $faker->unique()->randomElement($redshifts),
				"method_id" => $faker->randomElement($methods),
				"redshift_result" => $faker->randomFloat($nBMaxDecimals=3, $min = 0, $max = 10),
				"redshift_alt_result" => $faker->domainName()
			]);
		}
    }
}
