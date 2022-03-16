<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class ReportController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::all();

        return view('reports.index', compact('reports'));
    }

    public function clean()
    {
        $reports = Report::all();
        foreach($reports as $report)
        {
            if(\Carbon\Carbon::parse($report->created_at)->diffInDays(\Carbon\Carbon::now()) > 7)
            {
                $this->destroy($report);
            }
        }

    }

    public function destroy(Report $report)
    {
        Storage::delete($report->report);
        $report->delete();

        return back();
    }

}
