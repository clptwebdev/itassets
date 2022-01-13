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

        //If cached user_id is different to the auth()->user()->id then the data needs to be refreshed
        //This is becuase if two users with different roles may use the same machine and see other items they are not permitted for.
        if(Cache::has('user_id') && Cache::get('user_id') != auth()->user()->id){
            //If the User ID is different Flush all of the Cache
            Cache::flush();
            //Set the new cached user id to the current user
            Cache::set('user_id', auth()->user()->id);
        }

        //These are the total that will be needed at the End.
        $everything = 0;
        $cost = 0;
        $depreciation = 0;
        $deployed = 0;

        //Get the Users location which they have access to
        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        foreach($locations as $location){
            $id = $location->id;


            if( !Cache::has("assets-L{$id}-total") && 
                !Cache::has("assets-L{$id}-cost") &&
                !Cache::has("assets-L{$id}-dep") &&
                !Cache::has("assets-L{$id}-deploy") &&
                !Cache::has("assets-L{$id}-due") && 
                !Cache::has("assets-L{$id}-overdue")
            ){   
                /* This is to calculate all the assets for the individual schools and the grand total */
                Asset::updateCache();
            }

    
            /* This is to calculate the Accessories */
            if( !Cache::has("accessories-L{$id}-total") &&
                !Cache::has("accessories-L{$id}-cost") && 
                !Cache::has("accessories-L{$id}-depr") &&
                !Cache::has("accessories-L{$id}-deploy")
            ){
                Accessory::updateCache();
            }

            if( !Cache::has("components-L{$id}-total") &&
                !Cache::has("components-L{$id}-cost") &&
                !Cache::has("components-L{$id}-deploy")
            ){
                Component::updateCache();                
            }

            if( !Cache::has("consumables-L{$id}-total") &&
                !Cache::has("consumables-L{$id}-cost") &&
                !Cache::has("consumables-L{$id}-deploy")
            ){
                Consumable::updateCache();
            }

            if( !Cache::has("misc-L{$id}-total") &&
                !Cache::has("misc-L{$id}-cost") &&
                !Cache::has("misc-L{$id}-deploy")
            ){
                Miscellanea::updateCache();
            }
        }


        //This needs to be a foreach and run through all of the locations to get the values else everything will be Zero

        $everything += Cache::get('assets_total');
        $cost += Cache::get('assets_cost');
        $depreciation += Cache::get('assets_dep');
        $deployed += Cache::get('assets_deploy');

        //Accessories
        $everything += Cache::get('accessories_total');
        $cost += Cache::get('accessories_cost');
        $depreciation += Cache::get('accessories_dep');
        $deployed += Cache::get('accessories_deploy');

        /* Components Calcualtions */
        $deployed += Cache::get('components_deploy');

        //Consumables
        $deployed += Cache::get('consumables_deploy');

        //Miscellaneous
        $deployed += Cache::get('miscellaneous_deploy');

        
        Cache::rememberForever('count_everything', function() use($everything){
            return round($everything);
        });


        Cache::rememberForever('count_cost', function() use($cost){
            return round($cost);
        });

        Cache::rememberForever('count_depreciation', function() use($depreciation){
            return round($depreciation);
        });

        Cache::rememberForever('count_undeployed', function() use($deployed){
            return round($deployed);
        });

        if(!Cache::get('request_count')){
            \App\Models\Requests::updateCache();
        }

        if(!Cache::get('transfers_count')){
            \App\Models\Transfer::updateCache();
        }

        if(!Cache::get('archive_count')){
            \App\Models\Archive::updateCache();
        }

        if(Cache::get('count_undeployed') == 0){
            $undeployable = 100;
        }else{
            $undeployable = round(((Cache::get('count_everything') - Cache::get('count_undeployed')) / Cache::get('count_everything')) * 100);
        }

        return  Cache::get('assets_total');
        $obj = array(   'asset' => ['count' => Cache::get('assets_total'), 'cost' => Cache::get('assets_cost'), 'dep' => Cache::get('assets_dep')], 
                        'accessories' => ['count' => Cache::get('accessories_total'), 'cost' => Cache::get('accessories_cost'), 'dep' => Cache::get('accessories_dep')],
                        'components' => ['count' => Cache::get('components_total'), 'cost' => Cache::get('components_cost')],
                        'consumables' => ['count' => Cache::get('consumables_total'), 'cost' => Cache::get('consumables_cost')],
                        'miscellaneous' => ['count' => Cache::get('miscellaneous_total'), 'cost' => Cache::get('miscellaneous_cost')],
                        'requests' => ['count' => Cache::get('request_count')],
                        'transfer' => ['count' => Cache::get('transfers_count')],
                        'archived' => ['count' => Cache::get('archive_count')],
                        'everything' => ['count' => Cache::get('count_everything'), 'undeployable' => $undeployable],
                        'undeployable' => ['assets' => Cache::get('assets_deploy'), 'accessories' => Cache::get('accessories_deploy'), 'components' => Cache::get('components_deploy'), 'consumables' => Cache::get('consumables_deploy'), 'miscellanea' => Cache::get('miscellaneous_deploy')],
                        'audits' => ['due' => Cache::get('audits_due'), 'overdue' => Cache::get('audits_overdue')]
                    );



        return json_encode($obj);
    }


    public function clearCache(){
        Cache::flush();

        return back()->with('success_message', 'You have successfully cleared the cache.');
    }

}
