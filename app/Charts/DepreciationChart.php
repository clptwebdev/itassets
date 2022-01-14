<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Location;
use App\Models\Asset;

class DepreciationChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $locations = auth()->user()->locations;

        $years = [];
        $depreciation = [];
        foreach (range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year + 3) as $year){
            $years[] = $year;
        }

        $array = [];
        foreach($locations as $location){
            foreach($location->depreciations() as $id => $key){
                if(array_key_exists($id, $array)){
                    $array[$id] += $key;
                }else{
                    $array[$id] = $key;
                }
            }
        }

        $values = [...$array];
              
        return Chartisan::build()
            ->labels($years)
            ->dataset('Depreciation Cost', $values);

    }
}