<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Location;
use Illuminate\Http\Request;

class ComponentController extends Controller
{

    public function index()
    {
        return view('ComponentsDir.view', [
            "Components" => Component::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ComponentsDir.create', [
            "Locations" => Location::all(),
            "Status" => Status::all(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:255",
            "serial_no" => "required",
            "purchased_date" => "required",
            "purchased_cost" => "nullable",
            "supplier_id" => "required",
            "order_no" => "required",
            "warranty" => "nullable",
            "location_id" => "required",
            "notes" => "nullable",
        ]);
        Component::create([
            "name" => request("name"),
            "serial_no" => request("serial_no"),
            "purchased_date" => request("purchased_date"),
            "purchased_cost" => request("purchased_cost"),
            "supplier_id" => request("supplier_id"),
            "order_no" => request("order_no"),
            "warranty" => request("warranty"),
            "location_id" => request("location_id"),
            "notes" => request("notes"),
            session()->flash('success_message', request("name") . ' has been created successfully'),
        ]);

        return view("ComponentsDir.view");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        //
    }
}
