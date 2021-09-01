<?php

namespace App\Http\Controllers;

use App\Exports\LocationsExport;
use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    
    public function index()
    {
        $locations=Location::orderBy('name', 'asc')->get();
        return view('locations.view', ['locations'=>$locations]);
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|max:255',
            'address_1'=>'required',
            'city'=>'required',
            'county'=>'required',
            'postcode'=>'required',
            'email'=>'required|unique:locations|email',
            'telephone'=>'required|max:14',
        ]);

        Location::create($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'photo_id', 'icon'))->save();
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('location.index'));
    }
  
    public function show(Location $location)
    {
        return view('locations.show', compact('location'));
    }
 
    public function edit(Location $location)
    {
        if(auth()->user()->role_id==1){
            $locations =Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('locations.edit', compact('location','locations'));
    }

    public function update(Request $request, Location $location)
    {
        $validated=$request->validate([
            'name'=>'required|max:255',
            'address_1'=>'required',
            'city'=>'required',
            'county'=>'required',
            'postcode'=>'required',
            'email'=>['required', \Illuminate\Validation\Rule::unique('locations')->ignore($location->id), 'email'],
            'telephone'=>'required|max:14',
        ]);

        $location->fill($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'photo_id', 'icon'))->save();
        session()->flash('success_message', $location->name . ' has been updated successfully');
        return redirect(route('location.index'));
    }
   
    public function destroy(Location $location)
    {
        $name=$location->name;
        $location->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');
        return redirect(route('location.index'));
    }

    public function export(Location $location)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new LocationsExport, 'Location.csv');
    }

}
