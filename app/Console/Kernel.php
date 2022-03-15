<?php

namespace App\Console;

use App\Http\Controllers\BackupController;
use App\Models\Report;
use App\Models\Location;
use File;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel {

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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('\App\Http\Controllers\BackupController@createDb')->daily();
        //cleans all backups Monthly
        $schedule->call('\App\Http\Controllers\BackupController@dbClean')->monthly();

        //deletes all PDF's Monthly
        $schedule->call('\App\Http\Controllers\ReportController@clean')->everyMinute();

        $schedule->call(function() {
            $total = Cache::rememberForever('total_assets', function() {
                return \App\Models\Asset::count();
            });

            foreach(Location::all() as $location)
            {
                $total = Cache::rememberForever("location_{$location->id}_assets_total", function() {
                    return \App\Models\Asset::where('location_id', '=', $location->id)->count();
                });
            }

        })->daily();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
