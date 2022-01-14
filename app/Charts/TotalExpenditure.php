<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Miscellanea;

use Illuminate\Support\Facades\Cache;

class TotalExpenditure extends BaseChart
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
        $costs = [];
        $donations = [];
        foreach (range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year - 4) as $year){
            $years[] = $year;
            $total_cost = 0;
            $total_donations = 0;

            if(!Cache::has('assets_expenditure_'.$year) && !Cache::has('assets_donations_'.$year)){
                Cache::set('assets_expenditure_'.$year, round(Asset::expenditure($year, $locations)));
                Cache::set('assets_donations_'.$year, round(Asset::donations($year, $locations)));
            }
            
            $total_cost += Cache::get('assets_expenditure_'.$year);
            $total_donations += Cache::get('assets_donations_'.$year);

            if(!Cache::has('accessories_expenditure_'.$year) && !Cache::has('accessories_donations_'.$year)){
                Cache::set('accessories_expenditure_'.$year, round(Accessory::expenditure($year, $locations)));
                Cache::set('accessories_donations_'.$year, round(Accessory::donations($year, $locations)));
            }
            
            $total_cost += Cache::get('accessories_expenditure_'.$year);
            $total_donations += Cache::get('accessories_donations_'.$year);

            if(!Cache::has('components_expenditure_'.$year)){
                Cache::set('components_expenditure_'.$year, round(Component::expenditure($year, $locations)));
            }
            
            $total_cost += Cache::get('components_expenditure_'.$year);

            if(!Cache::has('consumables_expenditure_'.$year)){
                Cache::set('consumables_expenditure_'.$year, round(Consumable::expenditure($year, $locations)));
            }
            
            $total_cost += Cache::get('consumables_expenditure_'.$year);

            if(!Cache::has('miscellaneous_expenditure_'.$year)){
                Cache::set('miscellaneous_expenditure_'.$year, round(MIscellanea::expenditure($year, $locations)));
            }
            
            $total_cost += Cache::get('miscellaneous_expenditure_'.$year);

            

            $costs[] = round($total_cost);
            $donations[] = round($total_donations);

        }
        

        return Chartisan::build()
            ->labels(array_reverse($years))
            ->dataset('Costs', array_reverse($costs))
            ->dataset('Donations',  array_reverse($donations));
    }
}