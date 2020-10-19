<?php

namespace App\Console;

use App\Mail\CalcCompleteMail;
use App\redshifts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            //creating a timestamp one minute before the check, so that only calculations
            //submitted in the past minute are considered
            $time1 = Carbon::now()->toDateTimeString();
            echo $time1 . PHP_EOL;

            //creating the array of unique job IDs that have at least one completed calculation
            $someRedshifts = redshifts::SELECT('job_id')
				->where('status', 'COMPLETED')->distinct()->get();
			//echo $someRedshifts . PHP_EOL;

            // creating the array of unique job IDs from the someRedshifts array
			// that have at least one incomplete calculation
			$incompleteRedshifts = [];
			$incompleteFlag = 0;
			foreach($someRedshifts as $uniqueJobId){
				$incomplete = redshifts::SELECT('job_id')
					->where('status', '<>', 'COMPLETED')
					->where('job_id', $uniqueJobId->job_id)->distinct()->get();
				if((!empty($incomplete[0]))){
					//echo $incomplete. PHP_EOL;
					array_push($incompleteRedshifts, $incomplete);
					$incompleteFlag = 1;
				}
			}

			// todo - need to handle jobs where some calculations fail, here and in the rest of the site
			$finalArray = [];
			foreach($someRedshifts as $check){
				$skipFlag = 0;
				foreach($incompleteRedshifts as $index){
					if($check->job_id == $index[0]->job_id){
						$skipFlag = 1;
					}
				}
				if($skipFlag == 0){
					array_push($finalArray, $check->job_id);
				}
			}

			// doing logic to write a new array that only contains jobs that contain
			// no redshifts with status = processing, read, failed or submitted
			//the actual mail send logic.
			$emailDoneArray = [];
			foreach($finalArray as $final){
				$query = user::SELECT('email')
					->JOIN('jobs', 'jobs.user_id', '=', 'users.id')
					->where('job_id', $final)->first();
				// logic to not resend an email to a user who has already received an email
				$test = in_array($query->email, $emailDoneArray);

				array_push($emailDoneArray, $query->email);
				if($test){
					$completion = redshifts::where('job_id', $final)
						->update(['status' => 'READ']);
				}
				else{
					Mail::to($query->email)->send(new CalcCompleteMail());
					$completion = redshifts::where('job_id', $final)
						->update(['status' => 'READ']);
					echo "Email sent to " . $query->email . PHP_EOL;
				}
			}

		})->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
