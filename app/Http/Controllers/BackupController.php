<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('view', Backup::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Backups.');

        }
        dd(Storage::files('public/backups/Apollo-backup'));
        $files = collect(File::allFiles(Storage::disk('backups')->path('Apollo-Backup')))
            ->filter(function($file) {
                return $file->getExtension() == 'zip';
            })
            ->sortByDesc(function($file) {
                return $file->getCTime();
            })
            ->map(function($file) {
                return $file->getBaseName();
            });

        return view('backup.view', ['files' => $files]);
    }

    public function createDB()
    {

        Artisan::call("backup:run --only-db");

        return to_route("databasebackups.index")->with('success_message', 'A Backup of the database was completed!');

    }

    public function createFull()
    {

        Artisan::call("backup:run");

        return to_route("databasebackups.index")->with('success_message', 'A Backup of the Application was completed!');
    }

    public function dbClean()
    {

        $files = Storage::files('public/backups/Apollo-backup/');
        Storage::delete($files);

        return to_route("databasebackups.index")->with('success_message', 'Your database backups have been removed 0 Left!');
    }

}
