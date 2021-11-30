<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Miscellanea;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        if(auth()->user()->role_id == 1)
        {
            $locations = \App\Models\Location::with('asset', 'accessory', 'components', 'consumable', 'miscellanea', 'photo')->get();
            $assets = \App\Models\Asset::with('location', 'model', 'status')->get();
            $transfers = \App\Models\Transfer::count();
            $archived = \App\Models\Archive::count();
            $statuses = \App\Models\Status::with('assets', 'accessory', 'components', 'consumable', 'miscellanea', 'accessories')->get();
            $accessories = \App\Models\Accessory::all();
            $requests = \App\Models\Requests::whereStatus(0)->count();
            $components = \App\Models\Component::all();
            $consumables = \App\Models\Consumable::all();
            $miscellaneous = \App\Models\Miscellanea::all();
            $category = \App\Models\Category::withCount('assets')->orderBy('id', 'asc')->take(6)->get();
        } else
        {
            $locations = auth()->user()->locations;
            $assets = auth()->user()->location_assets;
            $transfers = \App\Models\Transfer::whereIn('location_from', $locations->pluck('id'))->orWhereIn('location_to', $locations->pluck('id'))->count();
            $archived = \App\Models\Archive::whereIn('location_id', $locations->pluck('id'))->count();
            $statuses = \App\Models\Status::with('assets', 'accessory', 'components', 'consumable', 'miscellanea')->get();
            $category = \App\Models\Category::withCount('assets')->orderBy('id', 'asc')->with('assets', 'accessories', 'components', 'consumables', 'miscellanea')->take(6)->get();
            $requests = \App\Models\Requests::whereStatus(0)->count();
            $accessories = Accessory::locationFilter(auth()->user()->locations->pluck('id'))->get();
            $components = Component::locationFilter(auth()->user()->locations->pluck('id'))->get();
            $consumables = Consumable::locationFilter(auth()->user()->locations->pluck('id'))->get();
            $miscellaneous = Miscellanea::locationFilter(auth()->user()->locations->pluck('id'))->get();
        }

        return view('dashboard',
            [
                'assets' => $assets,
                'transfers' => $transfers,
                'archived' => $archived,
                'statuses' => $statuses,
                'accessories' => $accessories,
                'components' => $components,
                'consumables' => $consumables,
                'miscellaneous' => $miscellaneous,
                'category' => $category,
                'requests' => $requests,
            ]
        );
    }
}
