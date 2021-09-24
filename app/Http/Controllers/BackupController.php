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
        $files = Storage::files('public/Apollo---Asset-Manager');

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

        Storage::download($request->file);
    }

    public function createDB()
    {

        Artisan::call("backup:run --only-db");

        return redirect("/databasebackups")->with('success_message', 'A Backup of the database was completed!');

    }

    public function createFull()
    {

        Artisan::call("backup:run");

        return redirect("/databasebackups")->with('success_message', 'A Backup of the Application was completed!');
    }

    public function dbClean()
    {
        $files = Storage::files('public/Apollo---Asset-Manager');
        Storage::delete($files);

        return redirect("/databasebackups")->with('success_message', 'Your database backups have been removed 0 Left!');
    }

}
