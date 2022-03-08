<?php

namespace App\Http\Controllers;

use App\Models\Backup;
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
        $files = Storage::files('public/Apollo-Asset-Manager');

        $zipFiles = array();

        foreach($files as $key => $val)
        {
            $val = str_replace("public/", "", $val);
            array_push($zipFiles, $val);
        }

        return view('backup.view', ['files' => $zipFiles]);
    }

    public function download(Request $request)
    {
        if(auth()->user()->cant('view', Backup::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Download Backups.');

        }
        Storage::download($request->file);
    }

    public function createDB()
    {
        if(auth()->user()->cant('create', Backup::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Create Backups.');

        }
        Artisan::call("backup:run --only-db");

        return to_route("databasebackups.index")->with('success_message', 'A Backup of the database was completed!');

    }

    public function createFull()
    {
        if(auth()->user()->cant('create', Backup::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Create Backups.');

        }
        Artisan::call("backup:run");

        return to_route("databasebackups.index")->with('success_message', 'A Backup of the Application was completed!');
    }

    public function dbClean()
    {
        if(auth()->user()->cant('delete', Backup::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Clean Backups.');

        }
        $files = Storage::files('public/Apollo---Asset-Manager');
        Storage::delete($files);

        return to_route("databasebackups.index")->with('success_message', 'Your database backups have been removed 0 Left!');
    }

}
