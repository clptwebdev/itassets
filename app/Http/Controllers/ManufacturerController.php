<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Location;

class ManufacturerController extends Controller
{
    public function show()
    {
        return view('Manufacturers.view', [
            "manufacturers"=>Manufacturer::all(),
        ]);

    }

    public function list()
    {
        return view("Manufacturers.create", [
            "manufacturerAmount"=>count(Manufacturer::all())
        ]);
    }

    public function create()
    {
        return view('Manufacturers.create');

    }

    public function edit(Manufacturer $manufacturers)
    {
        return view('Manufacturers.edit', [
            "manufacturer"=>$manufacturers,
        ]);

    }
    public function update(Manufacturer $manufacturers)
    {
        request()->validate([
            "name"=>"required|max:255",
            "supportPhone"=>"required|max:14",
            "supportUrl"=>"required",
            'supportEmail'=>['required', \Illuminate\Validation\Rule::unique('manufacturers')->ignore($manufacturers->id)],
            "PhotoId"=>"nullable",
        ]);
        $manufacturers->fill([
            "name"=>request("name"),
            "supportPhone"=>request("supportPhone"),
            "supportUrl"=>request("supportUrl"),
            "supportEmail"=>request("supportEmail"),
            "photoId"=>request("photoId")])->save();

        session()->flash('success_message', request("name") . ' has been updated successfully');

        return redirect('/manufacturers');
    }

    public function store(Manufacturer $manufacturers)
    {
        request()->validate([
            "name"=>"required|max:255",
            "supportPhone"=>"required|max:14",
            "supportUrl"=>"required",
            "supportEmail"=>['required', \Illuminate\Validation\Rule::unique('manufacturers')->ignore($manufacturers->supportEmail), 'email:rfc,dns,spoof,filter'],
            "PhotoId"=>"nullable",
        ]);
        Manufacturer::create([
            "name"=>request("name"),
            "supportPhone"=>request("supportPhone"),
            "supportUrl"=>request("supportUrl"),
            "supportEmail"=>request("supportEmail"),
            "photoId"=>request("photoId"),
            session()->flash('success_message', request("name") . ' has been created successfully'),
        ]);
        return redirect('/manufacturers');
    }

    public function destroy(Manufacturer $manufacturers)
    {
        $name=$manufacturers->name;
        $manufacturers->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');
        return redirect('/manufacturers');

    }

}
