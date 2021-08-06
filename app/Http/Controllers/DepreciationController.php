<?php

namespace App\Http\Controllers;

use App\Models\Depreciation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepreciationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depreciation  = Depreciation::all();
        return view('depreciation.view', compact('depreciation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Depreciation::create($request->only('name', 'years'))->save();
        session()->flash('success_message', $request->name.' has been added to the system');
        return redirect(route('depreciation.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Depreciation $depreciation)
    {
        return $depreciation->models;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Depreciation $depreciation)
    {
        $depreciation->fill($request->only('name', 'years'))->save();
        session()->flash('success_message', $request->name.' has been updated to the system');
        return redirect(route('depreciation.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depreciation $depreciation)
    {
        $name=$depreciation->name;
        $depreciation->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');
        return redirect(route('depreciation.index'));
    }
}
