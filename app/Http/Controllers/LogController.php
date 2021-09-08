<?php

namespace App\Http\Controllers;

use App\Exports\LogsExport;
use App\Models\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{

    public function index()
    {
        if (auth()->user()->cant('viewAll', auth()->user())) {
            return redirect(route('errors.forbidden', ['area', 'Logs', 'view']));
        }
        return view('logs.view', [
            "logs" => Log::all(),
        ]);
    }
    public function export(Request $request)
    {
        if (auth()->user()->cant('viewAll', auth()->user())) {
            return redirect(route('errors.forbidden', ['area', 'Logs', 'export']));
        }
        $logs = Log::all()->whereIn('id', json_decode($request->logs));
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new LogsExport($logs), "/public/csv/logs-ex-{$date}.csv");
        $url = asset("storage/csv/logs-ex-{$date}.csv");
        return redirect(route('logs.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }
}
