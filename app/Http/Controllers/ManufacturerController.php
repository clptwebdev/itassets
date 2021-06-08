<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Location;

class ManufacturerController extends Controller
{
//    public function create()
//    {
//        return view('Manufacturer.create');
//    }
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
            "supportEmail"=>['required', \Illuminate\Validation\Rule::unique('manufacturers')->ignore(Manufacturer::find($manufacturers)->first->supportEmail), 'email:rfc,dns,spoof,filter'],
            "PhotoId"=>"nullable",
        ]);
        $manufacturer=Manufacturer::find($manufacturers)->first();
        $manufacturer->name=request("name");
        $manufacturer->supportPhone=request("supportPhone");
        $manufacturer->supportUrl=request("supportUrl");
        $manufacturer->supportEmail=request("supportEmail");
        $manufacturer->photoId=request("photoId");
        session()->flash('success_message', $manufacturer->name . ' has been updated successfully');

        $manufacturer->save();

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
        $manufacturer=new Manufacturer();

        $manufacturer->name=request("name");
        $manufacturer->supportPhone=request("supportPhone");
        $manufacturer->supportUrl=request("supportUrl");
        $manufacturer->supportEmail=request("supportEmail");
        $manufacturer->photoId=request("photoId");
        session()->flash('success_message', $manufacturer->name . ' has been created successfully');

        $manufacturer->save();

        return redirect('/manufacturers');
    }


    public function destroy(Manufacturer $manufacturers )
    {
        $name = $manufacturers->name;
        $manufacturers->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect('/manufacturers');

    }

}
