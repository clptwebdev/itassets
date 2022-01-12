<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Miscellanea;
use App\Models\Requests;
use App\Models\Archive;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller {

    public function index()
    {
       

        //return dd($assets[0]);
        return view('dashboard');
    }

    public function statistics(){

        $everything = 0;

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }


        if(Cache::has('total_assets') && Cache::has('asset_cost') && Cache::has('asset_dep') && Cache::has('assets_deploy') && Cache::has('audits_due') && Cache::has('audits_overdue')){
            $everything += Cache::has('total_assets');
        }else{ 
            $assets = Asset::locationFilter($locations->pluck('id'))
                            ->with('model', 'status')
                            ->select('asset_model', 'purchased_cost', 'purchased_date', 'status_id', 'audit_date')
                            ->get()
                            ->map(function($item, $key) {
                                $item['depreciation_value'] = $item->depreciation_value();
                                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                return $item;
                            });

            $everything += $assets->count();
            $total = Cache::rememberForever('total_assets', function () use($assets){
                return $assets->count();
            });

            $cost_total = 0;
            $audits_due = 0;
            $audits_overdue = 0;
            $dep_total = 0;
            $deploy_assets = 0;

            foreach($assets as $asset){
                $cost_total += $asset->purchased_cost;
                $dep_total += $asset->depreciation_value;
                $audit_date = \Carbon\Carbon::parse($asset->audit_date);
                $now = \Carbon\Carbon::now();
                if($audit_date->isPast()){
                    $audits_overdue++;
                }elseif($audit_date->diffInMonths($now) < 3){
                    $audits_due++;
                }
                if($asset->deployable !== 1){ $deploy_assets++;}
            }

            $cost = Cache::rememberForever('asset_cost', function() use($cost_total){
                return round($cost_total);
            });

            $dep = Cache::rememberForever('asset_dep', function() use($dep_total){
                return round($dep_total);
            });

            $deploy = Cache::rememberForever('assets_deploy', function() use($deploy_assets){
                return round($deploy_assets);
            });

            $due = Cache::rememberForever('audits_due', function() use($audits_due){
                return round($audits_due);
            });

            $due = Cache::rememberForever('audits_overdue', function() use($audits_overdue){
                return round($audits_overdue);
            });
        }

        if(Cache::has('total_accessories') && Cache::has('accessories_cost') && Cache::has('accessories_dep') &&  Cache::has('accessories_deploy')){
            $everything += Cache::has('total_accessories');
        }else{
            $accessories = Accessory::locationFilter($locations->pluck('id'))
                                    ->with('status')
                                    ->select('purchased_cost', 'purchased_date', 'depreciation_id', 'status_id')
                                    ->get()
                                    ->map(function($item, $key) {
                                        $item['depreciation_value'] = $item->depreciation_value();
                                        $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                        return $item;
                                    });

            $total = Cache::rememberForever('total_accessories', function () use($accessories){
                return $accessories->count();
            });

            $everything += $accessories->count();

            $cost_total = 0;
            $dep_total = 0;
            $deploy_accessories = 0;

            foreach($accessories as $accessory){
                $cost_total += $accessory->purchased_cost;
                $dep_total += $accessory->depreciation_value;
                if($accessory->deployable !== 1){ $deploy_accessories++;}
            }

            $cost = Cache::rememberForever('accessories_cost', function() use($cost_total){
                return round($cost_total);
            });

            $dep = Cache::rememberForever('accessories_dep', function() use($dep_total){
                return round($dep_total);
            });

            $deploy = Cache::rememberForever('accessories_deploy', function() use($deploy_accessories){
                return round($deploy_accessories);
            });
        }
       
        if(Cache::has('total_components') && Cache::has('components_cost') && Cache::has('components_deploy')){
            $everything += Cache::has('total_components');
        }else{
            $components = Component::locationFilter($locations->pluck('id'))
                            ->select('purchased_cost', 'status_id')
                            ->get()
                            ->map(function($item, $key) {
                                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                return $item;
                            });

            $total = Cache::rememberForever('total_components', function () use($components){
                return $components->count();
            });

            $cost_total = 0;
            $everything += $components->count();
            $deploy_components = 0;

            foreach($components as $component){
                $cost_total += $component->purchased_cost;
                if($component->deployable != 1){ $deploy_components++;}
            }

            $cost = Cache::rememberForever('components_cost', function() use($cost_total){
                return round($cost_total);
            });

            $deploy = Cache::rememberForever('components_deploy', function() use($deploy_components){
                return round($deploy_components);
            });
        }

        if(Cache::has('total_consumables') && Cache::has('consumables_cost')){
            $everything += Cache::has('total_consumabless');
        }else{
            $consumables = Consumable::locationFilter($locations->pluck('id'))
                            ->select('purchased_cost', 'status_id')
                            ->get() 
                            ->map(function($item, $key) {
                                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                return $item;
                            });

            $total = Cache::rememberForever('total_consumables', function () use($consumables){
                return $consumables->count();
            });

            $cost_total = 0;
            $everything += $consumables->count();
            $deploy_consumables = 0;

            foreach($consumables as $consumable){
                $cost_total += $consumables->purchased_cost;
                if($consumable->deployable != 1){ $deploy_consumables++;}
            }

            $cost = Cache::rememberForever('consumables_cost', function() use($cost_total){
                return round($cost_total);
            });

            $deploy = Cache::rememberForever('consumables_deploy', function() use($deploy_consumables){
                return round($deploy_consumables);
            });
        }

        if(Cache::has('total_misc') && Cache::has('misc_cost')){
            $everything += Cache::has('total_misc');
        }else{

            $miscellanea = Miscellanea::locationFilter($locations->pluck('id'))
                            ->select('purchased_cost', 'status_id')
                            ->get()
                            ->map(function($item, $key) {
                                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                return $item;
                            });

            $total = Cache::rememberForever('total_misc', function () use($miscellanea){
                return $miscellanea->count();
            });

            $cost_total = 0;
            $everything += $miscellanea->count();
            $deploy_miscellanea = 0;

            foreach($miscellanea as $misc){
                $cost_total += $misc->purchased_cost;
                if($misc->deployable != 1){ $deploy_miscellanea++;}
            }

            $cost = Cache::rememberForever('misc_cost', function() use($cost_total){
                return round($cost_total);
            });

            $deploy = Cache::rememberForever('miscellanea_deploy', function() use($deploy_miscellanea){
                return round($deploy_miscellanea);
            });
        }

        $cost = Cache::rememberForever('count_everything', function() use($everything){
            return round($everything);
        });

        $undeployable = Cache::get('assets_deploy') + Cache::get('accessories_deploy') + Cache::get('components_deploy') + Cache::get('consumables_deploy') + Cache::get('miscellanea_deploy');

        $requests = Requests::all()->count();
        $transfers = Transfer::all()->count();
        $archived = Archive::all()->count();

        $obj = array(   'asset' => ['count' => Cache::get('total_assets'), 'cost' => Cache::get('asset_cost'), 'dep' => Cache::get('asset_dep')], 
                        'accessories' => ['count' => Cache::get('total_accessories'), 'cost' => Cache::get('accessories_cost'), 'dep' => Cache::get('accessories_dep')],
                        'components' => ['count' => Cache::get('total_components'), 'cost' => Cache::get('components_cost')],
                        'consumables' => ['count' => Cache::get('total_consumables'), 'cost' => Cache::get('consumables_cost')],
                        'miscellaneous' => ['count' => Cache::get('total_misc'), 'cost' => Cache::get('misc_cost')],
                        'requests' => ['count' => $requests],
                        'transfer' => ['count' => $transfers],
                        'archived' => ['count' => $archived],
                        'everything' => ['count' => Cache::get('count_everything'), 'undeployable' => round(((Cache::get('count_everything') - $undeployable) / Cache::get('count_everything')) * 100)],
                        'undeployable' => ['assets' => Cache::get('assets_deploy'), 'accessories' => Cache::get('accessories_deploy'), 'components' => Cache::get('components_deploy'), 'consumables' => Cache::get('consumables_deploy'), 'miscellanea' => Cache::get('miscellanea_deploy')],
                        'audits' => ['due' => Cache::get('audits_due'), 'overdue' => Cache::get('audits_overdue')]
                    );



        return json_encode($obj);
    }


    public function clear_cache(){
        return Cache::flush();
    }

}
