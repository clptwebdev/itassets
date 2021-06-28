<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\ComponentsExport;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Component;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ComponentController extends Controller {

    public function index()
    {
        return view('ComponentsDir.view', [
            "components" => Component::all(),
        ]);
    }

    public function create()
    {
        return view('ComponentsDir.create', [
            "locations" => Location::all(),
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
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
            "manufacturer_id" => request("manufacturer_id"),
            session()->flash('success_message', request("name") . ' has been created successfully'),
        ]);

        return redirect(route("components.index"));

    }

    public function show(Component $component)
    {
        return view('ComponentsDir.show', [
            "component" => $component,

        ]);
    }

    public function edit(Component $component)
    {
        return view('ComponentsDir.edit', [
            "component" => $component,
            "locations" => Location::all(),
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
        ]);
    }

    public function update(Request $request, Component $component)
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
        $component->fill([
            "name" => request("name"),
            "serial_no" => request("serial_no"),
            "purchased_date" => request("purchased_date"),
            "purchased_cost" => request("purchased_cost"),
            "supplier_id" => request("supplier_id"),
            "order_no" => request("order_no"),
            "warranty" => request("warranty"),
            "location_id" => request("location_id"),
            "manufacturer_id" => request("manufacturer_id"),
            "notes" => request("notes")])->save();
        session()->flash('success_message', request("name") . ' has been created successfully');
        return redirect(route("components.index"));
    }

    public function destroy(Component $component)
    {
        $name = $component->name;
        $component->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return redirect(route('components.index'));

    }
    public function export(Component $component)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new ComponentsExport, 'components.csv');

    }

}
