<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\ComponentsExport;
use App\Imports\ComponentsImport;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Component;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;

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

    public function createMany(Request $request)
    {

        $validation = Validator::make($request->all(), [
            "name.*" => "unique:Components,name|required|max:255",
//            'order_no.*' => 'required,string',
//            'serial_no.*' => 'required,string',
            'warranty.*' => 'int',

        ]);

        for($i = 0; $i < count($request->name); $i++)
        {
            $component = new Component;

            $component->name = $request->name[$i];

            $component->serial_no = $request->serial_no[$i];

            //check for already existing Status upon import if else create
            if($status = Status::where(["name" => $request->status_id[$i]])->first())
            {

            } else
            {
                $supplier = new Status;

                $status->name = $request->status_id[$i];
                $status->deployable = 1;

                $status->save();
            }
            $component->status_id = $status->id;

            $component->purchased_date = \Carbon\Carbon::parse($request->purchased_date[$i])->format("Y-m-d");

            $component->purchased_cost = $request->purchased_cost[$i];

            //check for already existing Suppliers upon import if else create
            if($supplier = Supplier::where(["name" => $request->supplier_id[$i]])->first())
            {

            } else
            {
                $supplier = new Supplier;

                $supplier->name = $request->supplier_id[$i];
                $supplier->email = 'info@' . str_replace(' ', '', strtolower($request->supplier_id[$i])) . '.com';
                $supplier->url = 'www.' . str_replace(' ', '', strtolower($request->supplier_id[$i])) . '.com';
                $supplier->telephone = "Unknown";
                $supplier->save();
            }

            $component->supplier_id = $supplier->id;

            //check for already existing Manufacturers upon import if else create
            if($manufacturer = Manufacturer::where(["name" => $request->manufacturer_id[$i]])->first())
            {

            } else
            {
                $manufacturer = new Manufacturer;

                $manufacturer->name = $request->manufacturer_id[$i];
                $manufacturer->supportEmail = 'info@' . str_replace(' ', '', strtolower($request->manufacturer_id[$i])) . '.com';
                $manufacturer->supportUrl = 'www.' . str_replace(' ', '', strtolower($request->manufacturer_id[$i])) . '.com';
                $manufacturer->supportPhone = "Unknown";
                $manufacturer->save();
            }
            $component->manufacturer_id = $manufacturer->id;
            $component->order_no = $request->order_no[$i];
            $component->warranty = $request->warranty[$i];
            //check for already existing Locations upon import if else create
            if($location = Location::where(["name" => $request->location_id[$i]])->first())
            {

            } else
            {
                $location = new Location;

                $location->name = $request->location_id[$i];
                $location->email = 'enquiries@' . str_replace(' ', '', strtolower($request->location_id[$i])) . '.co.uk';
                $location->telephone = "01902556360";
                $location->address_1 = "Unknown";
                $location->city = "Unknown";
                $location->postcode = "Unknown";
                $location->county = "West Midlands";
                $location->icon = "#222222";
                $location->save();
            }
            $component->location_id = $location->id;

            $component->notes = $request->notes[$i];

            $component->save();
        }

//        if($validation->fails())
//        {
//            return view('ComponentsDir.view', [
//                "names" => $request->name,
//            ]);
//        }
//
//        for($i = 0; $i < count($request->name); $i++)
//        {
//            Component::Create([
//                "name" => $request->name[$i],
//                "serial_no" => $request->serial_no[$i],
//                "status_id" => $request->status_id[$i],
//                "purchased_date" => $request->purchased_date[$i],
//                "purchased_cost" => $request->purchased_cost[$i],
//                "supplier_id" => $request->supplier_id[$i],
//                "manufacturer_id" => $request->manufacturer_id[$i],
//                "order_no" => $request->order_no[$i],
//                "warranty" => $request->warranty[$i],
//                "location_id" => $request->location_id[$i],
//                "notes" => $request->notes[$i],
//
//            ]);
//        }
        session()->flash('success_message', $i . ' has been created successfully');

        return redirect('/components');

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

    public function import(Request $request)
    {
        $path = $request->file("csv")->getRealPath();
        $import = new ComponentsImport;
        $import->import($path, null, \Maatwebsite\Excel\Excel::CSV);
        $row = [];
        $attributes = [];
        $errors = [];
        $values = [];
        $results = $import->failures();
        $importErrors = [];
        foreach($results->all() as $result)
        {
            $row[] = $result->row();
            $attributes[] = $result->attribute();
            $errors[] = $result->errors();
            $values[] = $result->values();
            $importErrors[] = [

                "row" => $result->row(),
                "attributes" => $result->attribute(),
                "errors" => $result->errors(),
                "value" => $result->values(),
            ];

        }

        if(! empty($importErrors))
        {
            $errorArray = [];
            $valueArray = [];

            foreach($importErrors as $error)
            {
                if(array_key_exists($error['row'], $errorArray))
                {
                    $errorArray[$error['row']] = $errorArray[$error['row']] . ',' . $error['attributes'];
                } else
                {
                    $errorArray[$error['row']] = $error['attributes'];
                }
                $valueArray[$error['row']] = $error['value'];
            }

            return view('ComponentsDir.import-errors', [
                "errorArray" => $errorArray,
                "valueArray" => $valueArray,
            ]);

        } else
        {

            return redirect('/components')->with('success_message', 'All Components were added correctly!');

        }

    }

}
