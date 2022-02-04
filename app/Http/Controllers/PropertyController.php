<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{

    public function index()
    {
        //Return the View All Properties
        if(auth()->user()->role_id == 1)
        {
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;
        }

        $properties = Property::locationFilter($locations->pluck('id')->toArray())->get();

        return view('property.view', compact('properties', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cant('create', Property::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Property', 'create']));
        }

        if(auth()->user()->role_id == 1)
        {
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;
        }

        return view('property.create', [
            "locations" => $locations
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
        if(auth()->user()->cant('create', Property::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Property', 'create']));
        }

        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0'
        ]);

        $property = new Property;

        $property->fill([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'value' => $request->value,
            'depreciation' => $request->depreciation,
            'type' => $request->type,
            'date' => $request->date,
        ])->save();

        session()->flash('success_message', $request->name . ' has been created successfully');

        return redirect(route('property.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        return view('property.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        return view('property.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        //
    }
}
