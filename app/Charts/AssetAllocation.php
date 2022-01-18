<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

/* class AssetAllocation extends BaseChart
{
    
    $locations = auth()->user()->locations;

    //If the Cache Exists

    foreach($locations as $location){
        $assets = Cache::rememberForever('location'.$id.'total', function(){
            return 
        });
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

    public function handler(Request $request): Chartisan
    {
        return Chartisan::build()
            ->labels(['First', 'Second', 'Third'])
            ->dataset('Sample', [1, 2, 3])
            ->dataset('Sample 2', [3, 2, 1]);
    }
} */