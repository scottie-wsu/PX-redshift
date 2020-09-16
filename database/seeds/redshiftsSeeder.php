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

		foreach(range(1,125) as $index){

			//print_r($jobUserId['user_id']);

			//$jobUserId = (array)$jobUserId;


			//$jobUserId = Jobs::select('user_id')->where('job_id', $jobCounter)->first()->toArray();
			//print_r($jobUserId['user_id']);
			//$jobCounter = $jobCounter+1;
			//$jobUserId = (array)$jobUserId;

			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_v' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_g' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_r' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_i' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_z' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'status' => 'READ',
				//'job_count' => 125-$index,
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs1),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId['user_id']
			]);

			$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			redshifts::where('calculation_id', $index)->update([
				'user_id' => $jobUserId['user_id']
			]);



		}

		foreach(range(126,250) as $index){
			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_v' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_g' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_r' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_i' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_z' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'status' => 'COMPLETED',
				//'job_count' => 250-$index,
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs2),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId[0]
			]);

			$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			redshifts::where('calculation_id', $index)->update([
				'user_id' => $jobUserId['user_id']
			]);
		}

		foreach(range(251,375) as $index){

			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_v' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_g' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_r' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_i' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_z' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'status' => 'PROCESSING',
				//'job_count' => 375-$index,
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs3),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId[0]
			]);

			$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			redshifts::where('calculation_id', $index)->update([
				'user_id' => $jobUserId['user_id']
			]);
		}


		foreach(range(376,500) as $index){

			redshifts::create([
				'assigned_calc_id' => $faker->randomNumber($nbDigits = 5), //FK
				'optical_u' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_v' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_g' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_r' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_i' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'optical_z' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_three_six' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_four_five' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_five_eight' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_eight_zero' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_J' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_H' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'infrared_K' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'radio_one_four' => $faker->randomFloat($nBMaxDecimals=8, $min = 0, $max = 99999999),
				'status' => 'SUBMITTED',
				//'job_id' => Jobs::select('job_id')->where('job_id', $jobs1[$i])->first(),
				'job_id' => $faker->randomElement($jobs4),
				//$uniqUserId = Jobs::select('user_id')->where('job_id', $jobs1[$index])->first();
				//'user_id' => $jobUserId[0]
			]);

			$redshiftJobId = redshifts::select('job_id')->where('calculation_id', $index)->first();
			$jobUserId = Jobs::select('user_id')->where('job_id', $redshiftJobId['job_id'])->first();
			redshifts::where('calculation_id', $index)->update([
				'user_id' => $jobUserId['user_id']
			]);
		}
    }
}