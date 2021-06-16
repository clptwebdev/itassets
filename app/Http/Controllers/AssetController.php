<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Fieldset;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
            'models'=>AssetModel::all(),
            'suppliers' => Supplier::all(),
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
        $validated = $request->validate([
            'asset_tag' => 'required',
        ]);

        $assetModel = AssetModel::findOrFail($request->asset_model);
        $fieldset = $assetModel->fieldset_id;


        Asset::create($request->only('asset_tag', 'asset_model', 'serial_no', 'location_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'status_id', 'audit_date'))->save();
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('assets.index'));
        
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

    public function model(AssetModel $model){
        if($model->fieldset_id != 0){
            $fieldset = Fieldset::findOrFail($model->fieldset_id);
            return view('assets.fields', compact('model', 'fieldset'));
        }else{
            return false;
        }
    }
}
