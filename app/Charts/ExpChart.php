<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class ExpChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $now = \Carbon\Carbon::now();
        $years = [$now->subYear()->format('Y'), $now->subYear()->format('Y'), $now->subYear()->format('Y')];
        return Chartisan::build()
            ->labels($years)
            ->dataset('Sample', [76, 21, 43])
            ->dataset('Sample 2', [38, 92, 12]);
    }
}