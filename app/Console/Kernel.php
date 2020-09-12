<?php

namespace App\Console;

use App\Mail\CalcCompleteMail;
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
            echo 'test1' . PHP_EOL;

            //creating a timestamp one minute before the check, so that only calculations
            //submitted in the past minute are considered
            //TODO: check this works
            $time1 = Carbon::now()->subMinute()->toDateTimeString();
            echo $time1 . PHP_EOL;
            //selecting user emails from calculations that have been submitted to DB by api team
            $query = user::SELECT('email')
                //->FROM('users')
                ->JOIN('redshifts', 'redshifts.user_id', '=', 'users.id')
                ->JOIN('calculations', function($join) use ($time1){
                    $join->on('redshifts.calculation_id', '=', 'calculations.galaxy_id')
                        ->where('calculations.created_at', '>=',  $time1);
                })->get();
            $message = "test email";
            echo dump($query) . PHP_EOL;

            if($query->empty()){
                Mail::to('scott.tripney@gmail.com')->send($message);
            }
            else{
                Mail::to('scott.tripney@gmail.com')->send(new CalcCompleteMail());
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
