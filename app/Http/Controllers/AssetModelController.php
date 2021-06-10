<?php

namespace App\Http\Controllers;

use App\Models\AssetModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('asset-models.view');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('asset-models.create');
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
            'name'=>'required|max:255',
            'model_no'=>'required',
        ]);

        AssetModel::create($request->only('name', 'manfacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'));
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('asset-models.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AssetModel $assetModel)
    {
        return view('asset-models.edit', ['assetModel' => $assetModel]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetModel $assetModel)
    {
        $validated = $request->validate([
            'name'=>'required|max:255',
            'model_no'=>'required',
        ]);

        $assetModel->fill($request->only('name', 'manfacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'))->save();
        session()->flash('success_message', $request->name.' has been updated successfully');
        return redirect(route('asset-models.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetModel $assetModel)
    {
        $name = $assetModel->name;
        $assetModel->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect(route('asset-models.index'));
    }
}
