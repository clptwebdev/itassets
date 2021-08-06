<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\componentErrorsExport;
use App\Exports\ComponentsExport;
use App\Imports\ComponentsImport;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Component;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;
use function PHPUnit\Framework\isEmpty;

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
            "supplier_id" => "required",
            "location_id" => "required",
            "notes" => "nullable",
            "status_id" => "nullable",
            'order_no' => 'required',
            'serial_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        Component::create([
            "name" => request("name"),
            "serial_no" => request("serial_no"),
            "status_id" => request("status_id"),
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
    public function importErrors(Request $request)
    {

        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        return \Maatwebsite\Excel\Facades\Excel::download(new componentErrorsExport($export), 'ComponentImportErrors.csv');
    }
    public function ajaxMany(Request $request)
    {
        if($request->ajax()){
            $validation = Validator::make($request->all(), [
                "name.*" => "required|max:255",
                'order_no.*' => 'required',
                'serial_no.*' => 'required',
                'warranty.*' => 'int',
                'location_id.*' => 'required|gt:0',
                'supplier_id.*' => 'required|gt:0',
                'purchased_date.*' => 'nullable|date',
                'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);

            if($validation->fails()){
                return $validation->errors();
            }else{
                for($i = 0; $i < count($request->name); $i++)
                {
                    $component = new Component;
                    $component->name = $request->name[$i];
                    $component->serial_no = $request->serial_no[$i];
                    $component->status_id = $request->status_id[$i];
                    $component->purchased_date = \Carbon\Carbon::parse(str_replace('/','-',$request->purchased_date[$i]))->format("Y-m-d");
                    $component->purchased_cost = $request->purchased_cost[$i];
                    $component->supplier_id = $request->supplier_id[$i];
                    $component->manufacturer_id = $request->manufacturer_id[$i];
                    $component->order_no = $request->order_no[$i];
                    $component->warranty = $request->warranty[$i];
                    $component->location_id = $request->location_id[$i];
                    $component->notes = $request->notes[$i];
                    $component->save();
                }

                session()->flash('success_message', 'You have successfully added all Components!');
                return 'Success';
            }
        }

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
            "supplier_id" => "required",
            "location_id" => "required",
            "notes" => "nullable",
            "status_id" => "nullable",
            'order_no' => 'required',
            'serial_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        $component->fill([
            "name" => request("name"),
            "serial_no" => request("serial_no"),
            "status_id" => request("status_id"),
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
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());


        if(in_array($result[0],$extensions)){
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
                $errorValues = [];

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

                    if(array_key_exists($error['row'], $errorValues))
                    {
                        $array = $errorValues[$error['row']];
                    }else{
                        $array = [];
                    }

                    foreach($error['errors'] as $e){
                        $array[$error['attributes']] = $e;
                    }
                    $errorValues[$error['row']] = $array;

                }


                return view('ComponentsDir.import-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "statuses"=>Status::all(),
                    "suppliers"=>Supplier::all(),
                    "locations"=>Location::all(),
                    "manufacturers"=>Manufacturer::all(),
                ]);

            } else
            {

                return redirect('/components')->with('success_message', 'All Components were added correctly!');

            }
        }else{
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return redirect(route('components.index'));
        }



}}
