<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\BusinessExport;
use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\FFE;
use App\Models\Location;
use App\Models\Machinery;
use App\Models\Miscellanea;
use App\Models\Requests;
use App\Models\Archive;
use App\Models\Software;
use App\Models\Transfer;
use App\Models\Property;
use App\Models\AUC;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller {

    public function index()
    {
        //return dd($assets[0]);
        return view('dashboard');
    }

    public function business()
    {
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->get();

        return view('dashboard.business', compact('locations'));
    }

    ////////////////////////////////////////
    ////// Top Bar Search Functions ////////
    ////////////////////////////////////////

    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $assets = Asset::searchFilter($request->name)->get();
        $FFE = FFE::searchFilter($request->name)->get();
        $accessory = Accessory::searchFilter($request->name)->get();

        $component = Component::searchFilter($request->name)->get();
        $misc = Miscellanea::searchFilter($request->name)->get();
        $consumable = Consumable::searchFilter($request->name)->get();
        $merged = collect([$FFE, $accessory, $component, $misc, $consumable, $assets]);
        $single = Collection::empty();
        //foreach $model then Foreach $item Push to a single collection
        foreach($merged as $merge)
        {
            foreach($merge as $item)
            {
                $single->push($item);
            }
        }

        return view("search.view", [
            'assets' => $single,
        ]);
    }

    ////////////////////////////////////////
    ////// Statistic Functions /////////////
    ////////////////////////////////////////

    public function statistics()
    {

        //If cached user_id is different to the auth()->user()->id then the data needs to be refreshed
        //This is becuase if two users with different roles may use the same machine and see other items they are not permitted for.
        if(Cache::has('user_id') && Cache::get('user_id') != auth()->user()->id)
        {
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
        $locations = auth()->user()->locations;
        //Check to see if the cache has been set and exists
        if(! Cache::has("property-total") &&
            ! Cache::has("property-cost") &&
            ! Cache::has("property-dep")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            Property::getCache($locations->pluck('id'));
        }

        //Get the Users location which they have access to
        $locations = auth()->user()->locations;
        if(! Cache::has("assets-total") &&
            ! Cache::has("assets-cost") &&
            ! Cache::has("assets-dep") &&
            ! Cache::has("assets-deploy") &&
            ! Cache::has("assets-due") &&
            ! Cache::has("assets-overdue")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            Asset::getCache($locations->pluck('id'));
        }
        //This needs to be a foreach and run through all of the locations to get the values else everything will be Zero
        $everything += Cache::get('assets_total');
        $cost += Cache::get('assets_cost');
        $depreciation += Cache::get('assets_dep');
        $deployed += Cache::get('assets_deploy');

        /* This is to calculate the Accessories */
        if(! Cache::has("accessories-total") &&
            ! Cache::has("accessories-cost") &&
            ! Cache::has("accessories-depr") &&
            ! Cache::has("accessories-deploy")
        )
        {
            Accessory::getCache($locations->pluck('id'));
        }

        //Accessories
        $everything += Cache::get('accessories_total');
        $cost += Cache::get('accessories_cost');
        $depreciation += Cache::get('accessories_dep');
        $deployed += Cache::get('accessories_deploy');

        /* This is to calculate the Components */
        if(! Cache::has("components-total") &&
            ! Cache::has("components-cost") &&
            ! Cache::has("components-deploy")
        )
        {
            Component::getCache($locations->pluck('id'));
        }

        /* Components Calcualtions */
        $deployed += Cache::get('components_deploy');

        if(! Cache::has("consumables-total") &&
            ! Cache::has("consumables-cost") &&
            ! Cache::has("consumables-deploy")
        )
        {
            Consumable::getCache($locations->pluck('id'));
        }

        //Consumables
        $deployed += Cache::get('consumables_deploy');

        if(! Cache::has("miscellaneous-total") &&
            ! Cache::has("miscellaneous-cost") &&
            ! Cache::has("miscellaneous-deploy")
        )
        {
            Miscellanea::getCache($locations->pluck('id'));
        }

        //Miscellaneous
        $deployed += Cache::get('miscellaneous_deploy');

        Cache::rememberForever('count_everything', function() use ($everything) {
            return round($everything);
        });

        Cache::rememberForever('count_cost', function() use ($cost) {
            return round($cost);
        });

        Cache::rememberForever('count_depreciation', function() use ($depreciation) {
            return round($depreciation);
        });

        Cache::rememberForever('count_undeployed', function() use ($deployed) {
            return round($deployed);
        });

        if(! Cache::get('request_count'))
        {
            \App\Models\Requests::updateCache();
        }

        if(! Cache::get('transfers_count'))
        {
            \App\Models\Transfer::updateCache();
        }

        if(! Cache::get('archive_count'))
        {
            \App\Models\Archive::updateCache();
        }

        if(Cache::get('count_undeployed') == 0)
        {
            $undeployable = 100;
        } else
        {
            $undeployable = round(((Cache::get('count_everything') - Cache::get('count_undeployed')) / Cache::get('count_everything')) * 100);
        }

        $obj = array(
            'asset' => ['count' => Cache::get('assets_total'), 'cost' => Cache::get('assets_cost'), 'dep' => Cache::get('assets_dep')],
            'property' => ['count' => Cache::get('property_total'), 'cost' => Cache::get('property_cost'), 'dep' => Cache::get('property_dep')],
            'accessories' => ['count' => Cache::get('accessories_total'), 'cost' => Cache::get('accessories_cost'), 'dep' => Cache::get('accessories_dep')],
            'components' => ['count' => Cache::get('components_total'), 'cost' => Cache::get('components_cost')],
            'consumables' => ['count' => Cache::get('consumables_total'), 'cost' => Cache::get('consumables_cost')],
            'miscellaneous' => ['count' => Cache::get('miscellaneous_total'), 'cost' => Cache::get('miscellaneous_cost')],
            'requests' => ['count' => Cache::get('request_count')],
            'transfer' => ['count' => Cache::get('transfers_count')],
            'archived' => ['count' => Cache::get('archive_count')],
            'everything' => ['count' => Cache::get('count_everything'), 'undeployable' => $undeployable],
            'undeployable' => ['assets' => Cache::get('assets_deploy'), 'accessories' => Cache::get('accessories_deploy'), 'components' => Cache::get('components_deploy'), 'consumables' => Cache::get('consumables_deploy'), 'miscellanea' => Cache::get('miscellaneous_deploy')],
            'audits' => ['due' => Cache::get('audits_due'), 'overdue' => Cache::get('audits_overdue')],
        );

        return json_encode($obj);
    }

    public function business_statistics()
    {

        //If cached user_id is different to the auth()->user()->id then the data needs to be refreshed
        //This is becuase if two users with different roles may use the same machine and see other items they are not permitted for.
        if(Cache::has('user_id') && Cache::get('user_id') != auth()->user()->id)
        {
            //If the User ID is different Flush all of the Cache
            Cache::flush();
            //Set the new cached user id to the current user
            Cache::set('user_id', auth()->user()->id);
        }

        

        //Get the Users location which they have access to
        $locations = auth()->user()->locations;
        //Check to see if the cache has been set and exists
        if(! Cache::has("property-total") &&
            ! Cache::has("property-cost") &&
            ! Cache::has("property-dep")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            Property::getCache($locations->pluck('id')->toArray());
        }

        //Check to see if the cache has been set and exists
        if(! Cache::has("aucs-total") &&
            ! Cache::has("aucs-cost") &&
            ! Cache::has("aucs-dep")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            AUC::getCache($locations->pluck('id'));
        }

        //Check to see if the cache has been set and exists
        if(! Cache::has("ffes-total") &&
            ! Cache::has("ffes-cost") &&
            ! Cache::has("ffes-dep")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            FFE::getCache($locations->pluck('id'));
        }

        //Check to see if the cache has been set and exists
        if(! Cache::has("machinery-total") &&
            ! Cache::has("machinery-cost") &&
            ! Cache::has("machinery-dep")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            Machinery::getCache($locations->pluck('id'));
        }

        //Get the Users location which they have access to
        $locations = auth()->user()->locations;
        if(! Cache::has("assets-total") &&
            ! Cache::has("assets-cost") &&
            ! Cache::has("assets-dep") &&
            ! Cache::has("assets-deploy") &&
            ! Cache::has("assets-due") &&
            ! Cache::has("assets-overdue")
        )
        {
            /* This is to calculate all the assets for the individual schools and the grand total */
            Asset::getCache($locations->pluck('id'));
        }

        /* This is to calculate the Accessories */
        if(! Cache::has("accessories-total") &&
            ! Cache::has("accessories-cost") &&
            ! Cache::has("accessories-depr") &&
            ! Cache::has("accessories-deploy")
        )
        {
            Accessory::getCache($locations->pluck('id'));
        }

        $obj = array(
            'asset' => ['count' => Cache::get('assets_total'), 'cost' => Cache::get('assets_cost'), 'dep' => Cache::get('assets_dep')],
            'property' => ['count' => Cache::get('property_total'), 'cost' => Cache::get('property_cost'), 'dep' => Cache::get('property_dep')],
            'auc' => ['count' => Cache::get('auc_total'), 'cost' => Cache::get('auc_cost'), 'dep' => Cache::get('auc_dep')],
            'ffe' => ['count' => Cache::get('ffe_total'), 'cost' => Cache::get('ffe_cost'), 'dep' => Cache::get('ffe_dep')],
            'machinery' => ['count' => Cache::get('machinery_total'), 'cost' => Cache::get('machinery_cost'), 'dep' => Cache::get('machinery_dep')],
            'accessories' => ['count' => Cache::get('accessories_total'), 'cost' => Cache::get('accessories_cost'), 'dep' => Cache::get('accessories_dep')],
        );

        return json_encode($obj);
    }

    public function clearCache()
    {
        Cache::flush();

        return back()->with('success_message', 'You have successfully cleared the cache.');
    }

}
