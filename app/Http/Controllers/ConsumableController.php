<?php

namespace App\Http\Controllers;



use App\Exports\consumableErrorsExport;
use App\Exports\consumableExport;
use App\Imports\consumableImport;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsumableController extends Controller
{
    public function newComment(Request $request){
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $consumable = Consumable::find($request->consumable_id);
        $consumable->comment()->create(['title'=>$request->title, 'comment'=>$request->comment, 'user_id'=>auth()->user()->id]);
        return redirect(route('consumables.show', $consumable->id));
    }

    public function index()
    {
        return view('consumable.view', [
            "consumables" => Consumable::all(),
        ]);
    }

    public function create()
    {
        return view('consumable.create', [
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
        Consumable::create([
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

        return redirect(route("consumables.index"));

    }
    public function importErrors(Request $request)
    {

        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        return \Maatwebsite\Excel\Facades\Excel::download(new consumableErrorsExport($export), 'ConsumablesImportErrors.csv');
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
                    $consumable = new Consumable;
                    $consumable->name = $request->name[$i];
                    $consumable->serial_no = $request->serial_no[$i];
                    $consumable->status_id = $request->status_id[$i];
                    $consumable->purchased_date = \Carbon\Carbon::parse(str_replace('/','-',$request->purchased_date[$i]))->format("Y-m-d");
                    $consumable->purchased_cost = $request->purchased_cost[$i];
                    $consumable->supplier_id = $request->supplier_id[$i];
                    $consumable->manufacturer_id = $request->manufacturer_id[$i];
                    $consumable->order_no = $request->order_no[$i];
                    $consumable->warranty = $request->warranty[$i];
                    $consumable->location_id = $request->location_id[$i];
                    $consumable->notes = $request->notes[$i];
                    $consumable->save();
                }

                session()->flash('success_message', 'You have successfully added all Consumables!');
                return 'Success';
            }
        }

    }

    public function show(Consumable $consumable)
    {
        return view('consumable.show', [
            "consumable" => $consumable,

        ]);
    }

    public function edit(Consumable $consumable)
    {
        return view('consumable.edit', [
            "consumable" => $consumable,
            "locations" => Location::all(),
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
        ]);
    }

    public function update(Request $request, Consumable $consumable)
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
        $consumable->fill([
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
        session()->flash('success_message', request("name") . ' has been updated successfully');

        return redirect(route("consumables.index"));
    }

    public function destroy(Consumable $consumable)
    {
        $name = $consumable->name;
        $consumable->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return redirect(route('consumables.index'));

    }

    public function export(Consumable $consumable)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new consumableExport, 'consumables.csv');

    }

    public function import(Request $request)
    {
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());


        if(in_array($result[0],$extensions)){
            $path = $request->file("csv")->getRealPath();
            $import = new consumableImport;
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


                return view('consumable.import-errors', [
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

                return redirect('/consumables')->with('success_message', 'All Consumables were added correctly!');

            }
        }else{
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return redirect(route('consumables.index'));
        }



    }
}
