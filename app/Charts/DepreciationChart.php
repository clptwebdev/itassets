<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Location;
use App\Models\Asset;
use App\Models\Accessory;


use Illuminate\Support\Facades\Cache;

class DepreciationChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $locations = auth()->user()->locations->pluck('id');

        $years = [];
        $values = [];

        foreach (range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year + 3) as $year){
            $years[] = $year;
            $total = 0;

            if(!Cache::has('assets_depreciation_'.$year)){
                Cache::set('assets_depreciation_'.$year, round(Asset::depreciation_total($year, $locations)));
            }

            $total += Cache::get('assets_depreciation_'.$year);

            if(!Cache::has('accessories_depreication_'.$year)){
                Cache::set('accessories_depreciation_'.$year, round(Accessory::depreciation_total($year, $locations)));
            }

            $total += Cache::get('accessories_depreication_'.$year);

            $values[] = $total;
        }


        return Chartisan::build()
            ->labels($years)
            ->dataset('Depreciation Cost', $values);

    }
}