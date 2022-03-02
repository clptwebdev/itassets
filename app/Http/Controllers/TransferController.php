<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Acessory;
use App\Models\Transfer;
use App\Models\Location;

class TransferController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'transfers']));
        }

        if(auth()->user()->role_id == 1)
        {
            $transfers = Transfer::all();
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;

            $location_ids = $locations->pluck('id');
            $transfers = Transfer::whereIn('location_from', $location_ids)->orWhereIn('location_to', $location_ids)->get();
        }

        return view('transfers.view', compact('transfers', 'locations'));
    }

    public function assets()
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'view']));
        }

        if(auth()->user()->role_id == 1)
        {
            $transfers = Transfer::whereModelType('asset')->get();
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;

            $location_ids = $locations->pluck('id');
            $transfers = Transfer::whereIn('location_from', $location_ids)->orWhereIn('location_to', $location_ids)->whereModelType('asset')->get();
        }

        $title = "Asset Transfers";

        return view('transfers.view', compact('transfers', 'locations', 'title'));
    }

    public function accessories()
    {
        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'accessories']));
        }

        if(auth()->user()->role_id == 1)
        {
            $transfers = Transfer::whereModelType('accessory')->get();
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;

            $location_ids = $locations->pluck('id');
            $transfers = Transfer::whereIn('location_from', $location_ids)->orWhereIn('location_to', $location_ids)->whereModelType('accessory')->get();
        }

        $title = "Accessory Transfers";

        return view('transfers.view', compact('transfers', 'locations', 'title'));
    }

    public function transfer(Request $request)
    {
        $asset = Asset::find($request->asset_id);
        $requests = Requests::create([
            'type' => 'transfer',
            'model_type' => $request->model_type,
            'model_id' => $asset->id,
            'location_to' => $request->location_id,
            'location_from' => $asset->location_id,
            'user_id' => auth()->user()->id,
            'status' => '0'
        ]);
        //Notify by email

    }

}
