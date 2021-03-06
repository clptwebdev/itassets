<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use App\Models\Location;

class ExpChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $location = Location::find($request->id);
        $years = [];
        $costs = [];
        $donations = [];
        foreach (range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year - 4) as $year){
            $years[] = $year;
            $costs[] = round($location->expenditure($year));
            $donations[] = round($location->donations($year));
        }
        

        return Chartisan::build()
            ->labels(array_reverse($years))
            ->dataset('Costs', array_reverse($costs))
            ->dataset('Donations',  array_reverse($donations));
    }
}