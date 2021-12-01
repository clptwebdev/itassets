<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Component;
use App\Models\Location;
use App\Models\Miscellanea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller {

    public function index()
    {
        if(auth()->user()->role_id == 1)
        {
            $users = User::all();
            $assetModel = AssetModel::all();
            $locations = \App\Models\Location::with('asset', 'accessory', 'components', 'consumable', 'miscellanea', 'photo')->get();
            $assets = \App\Models\Asset::with('location', 'model', 'status')->get();
            $accessories = \App\Models\Accessory::all();
            $components = \App\Models\Component::all();
            $miscellaneous = \App\Models\Miscellanea::all();
            $statuses = \App\Models\Status::all();
            $categories = \App\Models\Category::with('assets', 'accessories', 'components', 'consumables', 'miscellanea')->get();
            Cache::put('name', $categories, 60);
        } else
        {
            $categories = \App\Models\Category::with('assets', 'accessories', 'components', 'consumables', 'miscellanea')->get();
            $users = User::all();
            $assetModel = AssetModel::all();
            $statuses = \App\Models\Status::all();
            $assets = Asset::locationFilter(auth()->user()->locations->get());
            $components = Component::locationFilter(auth()->user()->locations->get());
            $accessories = Accessory::locationFilter(auth()->user()->locations->get());
            $miscellaneous = Miscellanea::locationFilter(auth()->user()->locations->get());
            $locations = Location::locationFilter(auth()->user()->locations->get());
        }

        return view('settings.view', [
            "users" => $users,
            "assets" => $assets,
            "components" => $components,
            "accessories" => $accessories,
            "miscellaneous" => $miscellaneous,
            "locations" => $locations,
            "categories" => $categories,
            "statuses" => $statuses,
            "assetModel" => $assetModel,
        ]);
    }

    public function accessories(){

    }
    public function assets(){

    }
    public function components(){

    }
    public function miscellaneous(){

    }

}
