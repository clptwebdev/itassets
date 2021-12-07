<?php

namespace App\Http\Controllers;

use App\Exports\LogsExport;
use App\Models\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', auth()->user()))
        {
            return redirect(route('errors.forbidden', ['area', 'Logs', 'view']));
        }

        return view('logs.view', [
            "logs" => Log::Latest('created_at')->paginate(),
        ]);
    }

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', auth()->user()))
        {
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

    public function clearFilter()
    {
        session()->forget(['log_type', 'log_search']);
        return redirect(route('logs.index'));
    }

    public function filter(Request $request)
    {
        $filtered = Log::select();
        if($request->isMethod('post'))
        {
            if($request->search !== null)
            {
                \Session::put('log_search', $request->search);
            }
            if($request->type !== null)
            {
                \Session::put('log_type', $request->type);
            }
        }

        if(session('log_search'))
        {
            $results = $filtered->logFilter(session('log_search'));
        }
        if(session('log_type'))
        {
            $results = $filtered->logTypeFilter(session('log_type'));
        }

        if($results->count() == 0)
        {
            if(session('log_search') && session('log_type') !== null){
                session()->flash('danger_message', "<strong>" . session('log_type') ." & ". session('log_search') . "</strong>" . ' could not be found! Please search for something else!');

            }elseif(session('log_search') == null){
                session()->flash('danger_message', "<strong>" . session('log_type')  . "</strong>" . ' could not be found! Please search for something else!');

            }elseif(session('log_type') == null){
                session()->flash('danger_message', "<strong>" . session('log_search')  . "</strong>" . ' could not be found! Please search for something else!');

            }

            return view("logs.view", [
                'logs' => Log::latest()->paginate(),

            ]);
        } else
        {
            return view("logs.view", [
                'logs' => $results->paginate(),

            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Log $log
     * @return \Illuminate\Http\Response
     */
    public
    function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Log $log
     * @return \Illuminate\Http\Response
     */
    public
    function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Log          $log
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, Log $log)
    {
        //
    }

    public
    function destroy()
    {
        Log::truncate();
        session()->flash('danger_message', 'All Logs have been cleared!');

        return view('logs.view', [
            "logs" => Log::Latest('created_at')->get(),
        ]);
    }

}
