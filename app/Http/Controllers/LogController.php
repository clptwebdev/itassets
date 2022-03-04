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
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Logs.');

        }

        return view('logs.view', [
            "logs" => Log::Latest('created_at')->paginate(),
        ]);
    }

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', auth()->user()))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Export Logs.');

        }
        $logs = Log::all()->whereIn('id', json_decode($request->logs));
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new LogsExport($logs), "/public/csv/logs-ex-{$date}.xlsx");
        $url = asset("storage/csv/logs-ex-{$date}.xlsx");

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
            if(session('log_search') && session('log_type') !== null)
            {
                session()->flash('danger_message', "<strong>" . session('log_type') . " & " . session('log_search') . "</strong>" . ' could not be found! Please search for something else!');

            } else if(session('log_search') == null)
            {
                session()->flash('danger_message', "<strong>" . session('log_type') . "</strong>" . ' could not be found! Please search for something else!');

            } else if(session('log_type') == null)
            {
                session()->flash('danger_message', "<strong>" . session('log_search') . "</strong>" . ' could not be found! Please search for something else!');

            }

            return view("logs.view", [
                'logs' => Log::latest()->paginate(),

            ]);
        } else
        {
            return view("logs.view", [
                'logs' => $results->latest()->paginate(),

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
        abort(404);
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
        abort(404);
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
        abort(404);
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
        abort(404);
    }

    public
    function destroy()
    {
        if(auth()->user()->cant('delete', Log::class))
        {
            return ErrorController::forbidden(route('logs.index'), 'Unauthorised to Delete Logs.');

        }
        Log::truncate();
        session()->flash('danger_message', 'All Logs have been cleared!');

        return view('logs.view', [
            "logs" => Log::Latest('created_at')->get(),
        ]);
    }

}
