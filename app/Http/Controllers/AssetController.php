<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Exports\AssetExport;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AssetController extends Controller
{

    public function index()
    {
        return view('assets.view', [
            "assets"=>Asset::all(),
        ]);
    }

    public function create(Asset $assets)
    {
        return view('assets.create', [
            "assets"=>$assets,
            "locations"=>Location::all(),
            "manufacturers"=>Manufacturer::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show(Asset $asset)
    {
        return view('assets.show', [
            "asset"=>$asset,
        ]);
    }


    public function edit(Asset $asset)
    {
        return view('assets.edit', [
            "asset"=>$asset,
            "locations"=>Location::all(),
            "manufacturers"=>Manufacturer::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Asset $asset)
    {
        $name=$asset->asset_tag;
        $asset->delete();
        session()->flash('danger_message', "#". $name . ' was deleted from the system');
        return redirect("/assets");
    }

    public function export(Asset $asset)
    {

//        return (new AssetExport)->download('assets.csv',\Maatwebsite\Excel\Excel::CSV,['Content-Type' => 'text/csv']);
//        return (new AssetExport)->download('invoices.xlsx');

        return \Maatwebsite\Excel\Facades\Excel::download(new AssetExport, 'invoices.xlsx');
    }

}
