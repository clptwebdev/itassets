<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Miscellanea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller {

    public function index()
    {
       

        //return dd($assets[0]);
        return view('dashboard');
    }

    public function statistics(){

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        if(Cache::has('total_assets') && Cache::has('asset_cost') && Cache::has('asset_dep')){

        }else{ 
            $assets = Asset::locationFilter($locations->pluck('id'))
                            ->with('model')
                            ->select('asset_model', 'purchased_cost', 'purchased_date')
                            ->get()
                            ->map(function($item, $key) {
                                $item['depreciation_value'] = $item->depreciation_value();
                                return $item;
                            });
            
        
            $total = Cache::rememberForever('total_assets', function () use($assets){
                return $assets->count();
            });

            $cost_total = 0;
            $dep_total = 0;

            foreach($assets as $asset){
                $cost_total += $asset->purchased_cost;
                $dep_total += $asset->depreciation_value;
            }

            $cost = Cache::rememberForever('asset_cost', function() use($cost_total){
                return round($cost_total);
            });

            $dep = Cache::rememberForever('asset_dep', function() use($dep_total){
                return round($dep_total);
            });
        }

        $accessories = Accessory::locationFilter($locations->pluck('id'))
                        ->select('purchased_cost', 'purchased_date', 'depreciation_id')
                        ->get()
                        ->map(function($item, $key) {
                            $item['depreciation_value'] = $item->depreciation_value();
                            return $item;
                        });

        $total = Cache::rememberForever('total_accessories', function () use($accessories){
            return $accessories->count();
        });

        $cost_total = 0;
        $dep_total = 0;

        foreach($accessories as $accessory){
            $cost_total += $accessory->purchased_cost;
            $dep_total += $accessory->depreciation_value;
        }

        $cost = Cache::rememberForever('accessories_cost', function() use($cost_total){
            return round($cost_total);
        });

        $dep = Cache::rememberForever('accessories_dep', function() use($dep_total){
            return round($dep_total);
        });

        $components = Component::locationFilter($locations->pluck('id'))
                        ->select('purchased_cost')
                        ->get();

        $total = Cache::rememberForever('total_components', function () use($components){
            return $components->count();
        });

        $cost_total = 0;

        foreach($components as $component){
            $cost_total += $component->purchased_cost;
        }

        $cost = Cache::rememberForever('components_cost', function() use($cost_total){
            return round($cost_total);
        });

        $consumables = Consumable::locationFilter($locations->pluck('id'))
                        ->select('purchased_cost')
                        ->get();

        $total = Cache::rememberForever('total_consumables', function () use($consumables){
            return $consumables->count();
        });

        $cost_total = 0;

        foreach($consumables as $consumable){
            $cost_total += $consumables->purchased_cost;
        }

        $cost = Cache::rememberForever('consumables_cost', function() use($cost_total){
            return round($cost_total);
        });

        $miscellanea = Miscellanea::locationFilter($locations->pluck('id'))
                        ->select('purchased_cost')
                        ->get();

        $total = Cache::rememberForever('total_misc', function () use($miscellanea){
            return $miscellanea->count();
        });

        $cost_total = 0;

        foreach($miscellanea as $misc){
            $cost_total += $misc->purchased_cost;
        }

        $cost = Cache::rememberForever('misc_cost', function() use($cost_total){
            return round($cost_total);
        });

        /* }else{
            $total = 0;
            $locations = auth()->user()->locations;
            foreach($locations as $location){
                Cache::rememberForever("location_{$location->id}_assets_total", function() use($location, $total){
                    return Asset::locationFilter([$location->id])->count();;
                });
                $total = $total + Cache::get("location_{$location->id}_assets_total");
            }
            Cache::put('total_assets', $total);
        } */

        /* //Accessories
        $total = 0; $depreciation = 0;
        foreach($accessories as $accessory){
            $total += $accessory->purchased_cost;
            $depreciation += $accessory->depreciation_value();
        }
        $accessory_total = $total; $accessory_depreciation = $depreciation;

        $total = 0;
        foreach($components as $component){
            $total = $total + $component->purchased_cost;
        }
        $component_total = $total;

        $total = 0;
        foreach($consumables as $consumable){
            $total = $total + $consumable->purchased_cost;
        }
        $consumable_total = $total;

        $total = 0;
        foreach($miscellaneous as $miscellanea){
            $total = $total + $miscellanea->purchased_cost;
        }
        $miscellanea_total = $total; */

        $obj = array(   'asset' => ['count' => Cache::get('total_assets'), 'cost' => Cache::get('asset_cost'), 'dep' => Cache::get('asset_dep')], 
                        'accessories' => ['count' => Cache::get('total_accessories'), 'cost' => Cache::get('accessories_cost'), 'dep' => Cache::get('accessories_dep')],
                        'components' => ['count' => Cache::get('total_components'), 'cost' => Cache::get('components_cost')],
                        'consumables' => ['count' => Cache::get('total_consumables'), 'cost' => Cache::get('consumables_cost')],
                        'miscellaneous' => ['count' => Cache::get('total_misc'), 'cost' => Cache::get('misc_cost')]);

        return json_encode($obj);
    }

}
