<?php

namespace App\Console;

use App\Http\Controllers\BackupController;
use App\Models\Report;
use App\Models\Location;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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
        //cleans all backups Monthly
        $schedule->call(function(){
            $files = Storage::files('public/Apollo---Asset-Manager');
            Storage::delete($files);
        })
            ->lastDayOfMonth()
            ->runInBackground();

        //deletes all csv's Monthly
        $schedule->call(function(){
            $files = Storage::files('/public/csv');
            Storage::delete($files);
        })->daily()->runInBackground();

        //deletes all PDF's Monthly
        $schedule->call(Report::clean())->daily()->runInBackground();

        $schedule->call(function(){
            $total = Cache::rememberForever('total_assets', function () {
                return \App\Models\Asset::count();
            });

            foreach(Location::all() as $location){
                $total = Cache::rememberForever("location_{$location->id}_assets_total", function () {
                    return \App\Models\Asset::where('location_id', '=', $location->id)->count();
                });
            }

        })->daily()->runInBackground();

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
