<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;
use App\Jobs;
use App\redshifts;

class redshiftsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
		$faker = Faker::create();
		$jobs = Jobs::all()->pluck('job_id')->toArray();
		//$users = Jobs::all()->pluck('user_id')->toArray();

		$jobs1 = array_slice($jobs, 0, 12);
		$jobs2 = array_slice($jobs, 12, 12);
		$jobs3 = array_slice($jobs, 24, 12);
		$jobs4 = array_slice($jobs, 36, 12);
		$max = 10;
		$nBMaxDecimals = 5;
		foreach(range(1,125) as $index){

			//here we're creating redshifts that are always unique galaxy IDs with unique data,
			//but in reality these may be duplicates due to having to create multiple redshift
			//rows with the same data (but unique primary key ids not created here) to be able
			//to do multiple methods per galaxy. While the calculations table may be able to
			//reference the same galaxy_id with a unique primary key id real_calculation_id
			//and have a different method_id for each row with the same galaxy_id, this would
			//mean that the status field for the redshift would have to reflect the status of ALL
			//methods being used on that galaxy, which may be very different and reduces granularity
			//of information available. just lots of issues in general with that approach
			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_v' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_g' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_r' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_i' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_z' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'status' => 'READ',
				//'job_count' => 125-$index,
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs1),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId['user_id']
			]);

			//$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			//$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			//redshifts::where('calculation_id', $index)->update([
			//	'user_id' => $jobUserId['user_id']
			//]);



		}

		foreach(range(126,250) as $index){
			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_v' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_g' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_r' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_i' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_z' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'status' => 'COMPLETED',
				//'job_count' => 250-$index,
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs2),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId[0]
			]);

			//$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			//$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			//redshifts::where('calculation_id', $index)->update([
			//	'user_id' => $jobUserId['user_id']
			//]);
		}

		foreach(range(251,375) as $index){

			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_v' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_g' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_r' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_i' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_z' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'status' => 'PROCESSING',
				//'job_count' => 375-$index,
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs3),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId[0]
			]);

			//$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			//$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			//redshifts::where('calculation_id', $index)->update([
			//	'user_id' => $jobUserId['user_id']
			//]);
		}


		foreach(range(376,500) as $index){

			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_v' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_g' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_r' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_i' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'optical_z' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals, $min = 0, $max),
				'status' => 'SUBMITTED',
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs4),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId[0]
			]);

			//$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			//$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			//redshifts::where('calculation_id', $index)->update([
			//	'user_id' => $jobUserId['user_id']
			//]);
		}
    }
}
