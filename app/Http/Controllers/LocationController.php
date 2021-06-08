<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::orderBy('name', 'asc')->get();
        return view('locations.view', ['locations'=>$locations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.create');
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
            'name' => 'required|max:255',
            'address_1' => 'required',
            'city' => 'required',
            'county' => 'required',
            'postcode' => 'required',
            'email' => 'required|unique:locations|email:rfc,dns,spoof,filter',
            'telephone' => 'required|max:14',
        ]);
        //
        $location->fill($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'photo_id', 'icon'))->save();
        session()->flash('success_message', $location->name.' has been updated successfully');
        return redirect(route('location.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
         //
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address_1' => 'required',
            'city' => 'required',
            'county' => 'required',
            'postcode' => 'required',
            'email' => ['required', \Illuminate\Validation\Rule::unique('locations')->ignore($location->id), 'email:rfc,dns,spoof,filter'],
            'telephone' => 'required|max:14',
        ]);       

        $location->fill($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'photo_id', 'icon'))->save();
        session()->flash('success_message', $location->name.' has been updated successfully');
        return redirect(route('location.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $name = $location->name;
        $location->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect(route('location.index'));
    }
}
