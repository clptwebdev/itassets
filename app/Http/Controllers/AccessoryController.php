<?php

namespace App\Http\Controllers;
use App\Exports\accessoryErrorsExport;
use App\Exports\accessoryExport;
use App\Imports\accessoryImport;
use App\Models\Accessory;
use App\Models\Category;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Storage;

class AccessoryController extends Controller
{
    public function index()
    {
        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessory', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $accessories = Accessory::all();
        }else{
            $accessories = auth()->user()->location_accessories;
        } 
        return view('accessory.view', compact('accessories'));
    }

    public function create()
    {
        if (auth()->user()->cant('create', $accesory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'create']));
        }
        
        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('accessory.create', [
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
        ]);
    }

    public function newComment(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $accessory = Accessory::find($request->accessory_id);
        $accessory->comment()->create(['title'=>$request->title, 'comment'=>$request->comment, 'user_id'=>auth()->user()->id]);
        return redirect(route('accessories.show', $accessory->id));
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessories', 'create']));
        }
        
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

        $accessory = Accessory::create($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes', 'photo_id'
        ));
        $accessory->category()->attach($request->category);
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
        if (auth()->user()->cant('view', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'view']));
        }

        return view('accessory.show', [
            "accessory" => $accessory,
        ]);
    }

    public function edit(Accessory $accessory)
    {
        if (auth()->user()->cant('update', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'edit']));
        }

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('accessory.edit', [
            "accessory" => $accessory,
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Accessory $accessory)
    {
        if (auth()->user()->cant('update', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'update']));
        }

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

        $accessory->fill($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes', 'photo_id'
        ))->save();
        session()->flash('success_message', $accessory->name.' has been Updated successfully');
        $accessory->category()->sync($request->category);
        return redirect(route("accessories.index"));
    }

    public function destroy(Accessory $accessory)
    {
        if (auth()->user()->cant('delete', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'delete']));
        }
        
        $name = $accessory->name;
        $accessory->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return redirect(route('accessories.index'));
    }

    public function export(Accessory $accessory)
    {
        if (auth()->user()->cant('view', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessory', 'export']));
        }
        return \Maatwebsite\Excel\Facades\Excel::download(new accessoryExport, 'Accessories.csv');
    }

    public function import(Request $request)
    {
        if (auth()->user()->cant('view', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessory', 'import']));
        }
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



    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessories', 'view pdf']));
        }
        $accessories = Accessory::withTrashed()->whereIn('id', json_decode($request->accessories))->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('accessory.pdf', compact('accessories'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/accessories-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/accessories-{$date}.pdf");
        return redirect(route('accessories.index'))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }

    public function downloadShowPDF(Accessory $accessory)
    {
        if (auth()->user()->cant('view', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'view pdf']));
        }

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('accessory.showPdf', compact('accessory'));
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/accessory-{$accessory->id}-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/accessory-{$accessory->id}-{$date}.pdf");
        return redirect(route('accessories.show', $accessory->id))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
        
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessories', 'Recycle Bin']));
        }
        if(auth()->user()->role_id == 1){
            $accessories = Accessory::onlyTrashed()->get();
        }else{
            $accessories = auth()->user()->location_accessories()->onlyTrashed();
        }
        return view('accessory.bin', compact('accessories'));
    }

    public function restore($id)
    {
        $accessory = Accessory::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $accessory)) {
            return redirect(route('errors.forbidden', ['component', $accessory->id, 'restore']));
        }
        $accessory->restore();
        session()->flash('success_message', "#". $accessory->name . ' has been restored.');
        return redirect("/accessories");
    }

    public function forceDelete($id)
    {
        $accessory = Accessory::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'Force Delete']));
        }
        $name=$accessory->name;
        $accessory->forceDelete();
        session()->flash('danger_message', "Accessory - ". $name . ' was deleted permanently');
        return redirect("/accessory/bin");
    }

}
