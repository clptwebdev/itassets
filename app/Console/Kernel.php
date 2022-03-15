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
        $schedule->call('\App\Http\Controllers\BackupController@createDb')->everyMinute();
        //cleans all backups Monthly
        $schedule->call(function() {
            $files = collect(File::allFiles(Storage::disk('backups')->path('Apollo-backup')))
                ->filter(function($file) {
                    return $file->getExtension() == 'zip';
                })
                ->sortByDesc(function($file) {
                    return $file->getCTime();
                })
                ->map(function($file) {
                    return $file->getBaseName();
                });
            $oldest = $files->reverse()->values()->take(20);
            Storage::delete($oldest);
        })->everyMinute();

        //deletes all csv's Monthly
//        $schedule->call(function() {
//            $files = Storage::files('/public/csv');
//            Storage::delete($files);
//        })->everyMinute();

        //deletes all PDF's Monthly
//        $schedule->call(Report::clean())->everyMinute();

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
