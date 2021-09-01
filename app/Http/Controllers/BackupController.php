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

//        foreach (\Illuminate\Support\Facades\Storage::files('Apollo---Asset-Manager') as $filename)
//        {
//            $file = \Illuminate\Support\Facades\Storage::get($filename);
//        }
//        return view('backup.view', [
//            "file" => $file,
//            "filename" => $filename,
//        ]);


        $files = Storage::files('public/Apollo---Asset-Manager');

        $zipFiles = array();

        foreach ($files as $key => $val) {
            $val = str_replace("public/","",$val);
            array_push($zipFiles, $val);
        }


        return view('backup.view', ['files' => $zipFiles]);
    }

    public function download(Request $request){
        
        Storage::download($request->file);
//        return (new Response($file, 200))
//            ->header('Content-Type', 'file/zip');
    }


    public function createDB()
    {

        Artisan::call("backup:run --only-db");


        return redirect("/databasebackups")->with('success_message',  'A Backup of the database was completed!');

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

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Backup $backup
     * @return \Illuminate\Http\Response
     */
    public function show(Backup $backup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Backup $backup
     * @return \Illuminate\Http\Response
     */
    public function edit(Backup $backup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Backup       $backup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Backup $backup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Backup $backup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Backup $backup)
    {
        //
    }

}
