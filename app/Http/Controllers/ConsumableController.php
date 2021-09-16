<?php

namespace App\Http\Controllers;



use App\Exports\consumableErrorsExport;
use App\Exports\consumableExport;
use App\Imports\consumableImport;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Storage;

class ConsumableController extends Controller
{

    public function newComment(Request $request)
    {
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
        if (auth()->user()->cant('viewAll', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'Consumables', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $consumables = Consumable::all();
        }else{
            $consumables = auth()->user()->location_consumables;
        }
        return view('consumable.view', compact('consumables'));
    }

    public function create()
    {
        if (auth()->user()->cant('create', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'Consumables', 'create']));
        }

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('consumable.create', [
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'Consumables', 'create']));
        }

        $request->validate([
            "name" => "required|max:255",
            "supplier_id" => "required",
            "location_id" => "required",
            "notes" => "nullable",
            "status_id" => "nullable",
            'order_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $consumable = Consumable::create($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes', 'photo_id'
        ));
        $consumable->category()->attach($request->category);
        return redirect(route("consumables.index"));

    }

    public function importErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if (auth()->user()->cant('viewAll', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'Consumables', 'export']));
        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new consumableErrorsExport($export), "/public/csv/consumables-errors-{$date}.csv");
        $url = asset("storage/csv/consumables-errors-{$date}.csv");
        return redirect(route('consumables.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
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
                    $consumable->photo_id =  0;

                    $consumable->save();
                }

                session()->flash('success_message', 'You have successfully added all Consumables!');
                return 'Success';
            }
        }

    }

    public function show(Consumable $consumable)
    {
        if (auth()->user()->cant('create', $consumable)) {
            return redirect(route('errors.forbidden', ['consumables', $consumable->id, 'view']));
        }
        return view('consumable.show', ["consumable" => $consumable]);
    }

    public function edit(Consumable $consumable)
    {
        if (auth()->user()->cant('update', $consumable)) {
            return redirect(route('errors.forbidden', ['consumables', $consumable->id, 'update']));
        }

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('consumable.edit', [
            "consumable" => $consumable,
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            "categories" => Category::all(),
        ]);
    }

    public function update(Request $request, Consumable $consumable)
    {
        if (auth()->user()->cant('update', $consumable)) {
            return redirect(route('errors.forbidden', ['consumables', $consumable->id, 'update']));
        }

        $request->validate([
            "name" => "required|max:255",
            "supplier_id" => "required",
            "location_id" => "required",
            "notes" => "nullable",
            "status_id" => "nullable",
            'order_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $consumable->fill($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes', 'photo_id'
        ))->save();
        $consumable->category()->sync($request->category);
        session()->flash('success_message', $consumable->name. ' has been updated successfully');

        return redirect(route("consumables.index"));
    }

    public function destroy(Consumable $consumable)
    {
        if (auth()->user()->cant('delete', $consumable)) {
            return redirect(route('errors.forbidden', ['consumables', $consumable->id, 'delete']));
        }
        $name = $consumable->name;
        $consumable->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return redirect(route('consumables.index'));

    }

    public function export(Consumable $consumable)
    {
        if (auth()->user()->cant('export', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'consumables', 'export']));
        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new consumableExport, "/public/csv/consumables-ex-{$date}.csv");
        $url = asset("storage/csv/consumables-ex-{$date}.csv");
        return redirect(route('consumables.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();


    }

    public function import(Request $request)
    {
        if (auth()->user()->cant('create', Consumable::class)) {
            return redirect(route('errors.forbidden', ['consumables', $consumable->id, 'import']));
        }

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

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAll', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'consumables', 'export pdf']));
        }
        $consumables = Consumable::withTrashed()->whereIn('id', json_decode($request->consumables))->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('consumable.pdf', compact('consumables'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/consumables-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/consumables-{$date}.pdf");
        return redirect(route('consumables.index'))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }

    public function downloadShowPDF(Consumable $consumable)
    {
        if (auth()->user()->cant('export', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'consumables', 'export pdf']));
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('consumable.showPdf', compact('consumable'));
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/consumable-{$consumable->id}-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/consumable-{$consumable->id}-{$date}.pdf");
        return redirect(route('consumables.show', $consumable->id))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if (auth()->user()->cant('recycleBin', Consumable::class)) {
            return redirect(route('errors.forbidden', ['area', 'consumables', 'recycle bin']));
        }

        if(auth()->user()->role_id == 1){
            $consumables = Consumable::onlyTrashed()->get();
        }else{
            $consumables = auth()->user()->location_consumables()->onlyTrashed();
        }
        return view('consumable.bin', compact('consumables'));
    }

    public function restore($id)
    {
        $consumable = Consumable::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $consumable)) {
            return redirect(route('errors.forbidden', ['consumable', $consumable->id, 'restore']));
        }
        $consumable->restore();
        session()->flash('success_message', "#". $consumable->name . ' has been restored.');
        return redirect("/consumables");
    }

    public function forceDelete($id)
    {
        $consumable = Consumable::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $consumable)) {
            return redirect(route('errors.forbidden', ['consumable', $consumable->id, 'remove']));
        }
        $name=$consumable->name;
        $consumable->forceDelete();
        session()->flash('danger_message', "Consumable - ". $name . ' was deleted permanently');
        return redirect("/consumable/bin");
    }

    public function changeStatus(Consumable $consumable, Request $request)
    {
        $consumable->status_id = $request->status;
        $consumable->save();
        session()->flash('success_message', $consumable->name . ' has had its status changed successfully');
        return redirect(route('consumables.show', $consumable->id));
    }
}
