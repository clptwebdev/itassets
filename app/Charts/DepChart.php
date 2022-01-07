<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use App\Models\Location;

class DepChart extends BaseChart
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
        $depreciation = [];
        foreach (range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year + 3) as $year){
            $years[] = $year;
        }

        $array = [];

        foreach($location->depreciations() as $id => $key){
            $array[] = round($key);
        }
        

        return Chartisan::build()
            ->labels($years)
            ->dataset('Depreciation Cost', $array);
    }
}