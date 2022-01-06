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
        $location = Location::find($request->id);
        $now = \Carbon\Carbon::now();
        $year1 = \Carbon\Carbon::now()->subYear();
        $year2 = \Carbon\Carbon::now()->subYears(2);
        $year3 = \Carbon\Carbon::now()->subYears(3);
        $year4 = \Carbon\Carbon::now()->subYears(4);
        $years = [$now->format('Y'), $year1->format('Y'), $year2->format('Y'), $year3->format('Y'), $year4->format('Y')];
        $expenditure = [
            $location->expenditure($now->format('Y')), 
            $location->expenditure($year1->format('Y')), 
            $location->expenditure($year2->format('Y')), 
            $location->expenditure($year3->format('Y')), 
            $location->expenditure($year4->format('Y')), 
        ];
        return Chartisan::build()
            ->labels($years)
            ->dataset('Costs', $expenditure)
            ->dataset('Donations', [38, 92, 12]);
    }
}