<?php

namespace App\Http\Controllers;
use App\Exports\accessoryErrorsExport;
use App\Exports\accessoryExport;
use App\Imports\accessoryImport;
use App\Models\Accessory;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccessoryController extends Controller
{
    public function index()
    {
        return view('accessory.view', [
            "accessories" => Accessory::all(),
        ]);
    }

    public function create()
    {
        return view('accessory.create', [
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

        Accessory::create([
            "name" => request("name"),
            "status_id" => request("status_id"),
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

        return redirect(route("accessories.index"));

    }
    public function importErrors(Request $request)
    {

        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        return \Maatwebsite\Excel\Facades\Excel::download(new accessoryErrorsExport($export), 'AccessoryImportErrors.csv');
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
                    $accessory = new Accessory;
                    $accessory->name = $request->name[$i];
                    $accessory->serial_no = $request->serial_no[$i];
                    $accessory->status_id = $request->status_id[$i];
                    $accessory->purchased_date = \Carbon\Carbon::parse(str_replace('/','-',$request->purchased_date[$i]))->format("Y-m-d");
                    $accessory->purchased_cost = $request->purchased_cost[$i];
                    $accessory->supplier_id = $request->supplier_id[$i];
                    $accessory->manufacturer_id = $request->manufacturer_id[$i];
                    $accessory->order_no = $request->order_no[$i];
                    $accessory->warranty = $request->warranty[$i];
                    $accessory->location_id = $request->location_id[$i];
                    $accessory->notes = $request->notes[$i];
                    $accessory->save();
                }

                session()->flash('success_message', 'You have successfully added all Accessories!');
                return 'Success';
            }
        }

    }

    public function show(Accessory $accessory)
    {
        return view('accessory.show', [
            "accessory" => $accessory,

        ]);
    }

    public function edit(Accessory $accessory)
    {
        return view('accessory.edit', [
            "accessory" => $accessory,
            "locations" => Location::all(),
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
        ]);
    }

    public function update(Request $request, Accessory $accessory)
    {
        $request->validate([
            "name" => "required|max:255",
            "supplier_id" => "required",
            "location_id" => "required",
            "notes" => "nullable",
            'order_no' => 'required',
            'serial_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        $accessory->fill([
            "name" => request("name"),
            "status_id" => request("status_id"),
            "serial_no" => request("serial_no"),
            "purchased_date" => request("purchased_date"),
            "purchased_cost" => request("purchased_cost"),
            "supplier_id" => request("supplier_id"),
            "order_no" => request("order_no"),
            "warranty" => request("warranty"),
            "location_id" => request("location_id"),
            "manufacturer_id" => request("manufacturer_id"),
            "notes" => request("notes")])->save();
        session()->flash('success_message', request("name") . ' has been Updated successfully');

        return redirect(route("accessories.index"));
    }

    public function destroy(Accessory $accessory)
    {
        $name = $accessory->name;
        $accessory->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return redirect(route('accessories.index'));

    }

    public function export(Accessory $accessory)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new accessoryExport, 'Accessories.csv');

    }

    public function import(Request $request)
    {
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());


        if(in_array($result[0],$extensions)){
            $path = $request->file("csv")->getRealPath();
            $import = new accessoryImport;
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


                return view('accessory.import-errors', [
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

                return redirect('/accessories')->with('success_message', 'All Accessories were added correctly!');

            }
        }else{
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return redirect(route('accessories.index'));
        }



    }}
