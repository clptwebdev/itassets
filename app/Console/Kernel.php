<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        $schedule->command('backup:run  --only-db')
            ->daily()
            ->runInBackground()
        ->onFailure(function(){
            echo ("This has not been successful please try again later!");
        })
        ->onSuccess(function(){
            echo ("This Database has backup has been created and stored in storage/app/Apollo---Asset-Manager Within your application");

        });
        $schedule->command('backup:clean')
            ->lastDayOfMonth()
            ->runInBackground();
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
