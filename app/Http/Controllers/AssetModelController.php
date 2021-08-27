<?php

namespace App\Http\Controllers;

use App\Models\AssetModel;
use App\Models\Depreciation;
use App\Models\Fieldset;
use App\Models\Manufacturer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetModelController extends Controller
{
    public function index()
    {
        return view('asset-models.view');
    }

    public function create()
    {
        $mans = Manufacturer::all();
        $fieldsets = Fieldset::all();
        $depreciation = Depreciation::all();
        return view('asset-models.create', compact('fieldsets', 'mans', 'depreciation'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=>'required|max:255',
            'model_no'=>'required',
        ]);

        AssetModel::create($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'));
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('asset-models.index'));
    }

    public function show(AssetModel $assetModel)
    {
        return view('asset-models.show', compact('assetModel'));
    }

    public function edit(AssetModel $assetModel)
    {
        $depreciation = Depreciation::all();
        $mans = Manufacturer::all();
        $fieldsets = Fieldset::all();
        return view('asset-models.edit', compact('fieldsets', 'mans', 'assetModel', 'depreciation'));
    }

    public function update(Request $request, AssetModel $assetModel)
    {
        $validated = $request->validate([
            'name'=>'required|max:255',
            'model_no'=>'required',
        ]);

        $assetModel->fill($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'))->save();
        session()->flash('success_message', $request->name.' has been updated successfully');
        return redirect(route('asset-models.index'));
    }

    public function destroy(AssetModel $assetModel)
    {
        $name = $assetModel->name;
        $assetModel->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect(route('asset-models.index'));
    }
}
