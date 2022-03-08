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

        $locations = auth()->user()->locations;
        $location_ids = $locations->pluck('id');
        $transfers = Transfer::whereIn('location_from', $location_ids)->orWhereIn('location_to', $location_ids)->get();

        return view('transfers.view', compact('transfers', 'locations'));
    }

    public function assets()
    {

        $locations = auth()->user()->locations;

        $location_ids = $locations->pluck('id');
        $transfers = Transfer::whereIn('location_from', $location_ids)->orWhereIn('location_to', $location_ids)->whereModelType('asset')->get();

        $title = "Asset Transfers";

        return view('transfers.view', compact('transfers', 'locations', 'title'));
    }

    public function accessories()
    {

        $locations = auth()->user()->locations;

        $location_ids = $locations->pluck('id');
        $transfers = Transfer::whereIn('location_from', $location_ids)->orWhereIn('location_to', $location_ids)->whereModelType('accessory')->get();

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
            'status' => '0',
        ]);
        //Notify by email

    }

}
