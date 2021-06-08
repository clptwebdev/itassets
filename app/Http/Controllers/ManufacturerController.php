<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;

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

    public function store()
    {
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

    public function update()
    {

    }public function delete()
    {

    }

}
