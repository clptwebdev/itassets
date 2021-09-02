<?php

namespace App\Http\Controllers;

use App\Exports\LocationsExport;
use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    
    public function index()
    {
        if (auth()->user()->cant('viewAny', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Locations', 'view']));
        }

        if(auth()->user()->role_id==1){
            $locations =Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('locations.view', ['locations'=>$locations]);
    }

    public function create()
    {
        if (auth()->user()->cant('create', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Locations', 'create']));
        }

        return view('locations.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Locations', 'create']));
        }

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
        if (auth()->user()->cant('view', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'view']));
        }

        return view('locations.show', compact('location'));
    }
 
    public function edit(Location $location)
    {
        if (auth()->user()->cant('update', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'edit']));
        }

        if(auth()->user()->role_id==1){
            $locations =Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('locations.edit', compact('location','locations'));
    }

    public function update(Request $request, Location $location)
    {
        if (auth()->user()->cant('update', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'edit']));
        }

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
        if (auth()->user()->cant('delete', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'delete']));
        }

        $name=$location->name;
        $location->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');
        return redirect(route('location.index'));
    }

    public function export(Location $location)
    {
        if (auth()->user()->cant('viewAny', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'locations', 'export']));
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new LocationsExport, 'Location.csv');
    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAny', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Location', 'View PDF']));
        }

        if(auth()->user()->role_id==1){
            $locations =Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('locations.pdf', compact('locations'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/locations-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/locations-{$date}.pdf");
        return redirect(route('location.index'))
            ->with('success_message', "Your Reprot has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();

    }

    public function downloadShowPDF(Location $location)
    {
        if (auth()->user()->cant('view', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'View PDF']));
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('locations.showPdf', compact('location'));

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        //return $pdf->download("{$location->name}-{$date}.pdf");
        Storage::put("public/reports/{$location->name}-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/{$location->name}-{$date}.pdf");
        return redirect(route('location.show', $location->id))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }

}
