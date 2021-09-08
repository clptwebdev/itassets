<?php

namespace App\Http\Controllers;

use App\Exports\AssetExport;
use App\Exports\componentErrorsExport;
use App\Exports\ComponentsExport;
use App\Imports\ComponentsImport;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
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
use PDF;

class ComponentController extends Controller {

    public function index()
    {
        if (auth()->user()->cant('viewAll', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Components', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $components = Component::all();
        }else{
            $components = auth()->user()->location_components;
        }
        return view('ComponentsDir.view', ["components" => $components]);
    }

    public function create()
    {
        if (auth()->user()->cant('create', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Components', 'create']));
        }

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('ComponentsDir.create', [
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Component', 'Create']));
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
        $component = Component::create($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes'
        ));
        $component->category()->attach($request->category);
        return redirect(route("components.index"));
    }

    public function importErrors(Request $request)
    {
            $export = $request['name'];
            $code = (htmlspecialchars_decode($export));
            $export = json_decode($code);

            if (auth()->user()->cant('viewAll', Component::class)) {
                return redirect(route('errors.forbidden', ['area', 'Components', 'export']));
            }
                
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new componentErrorsExport($export), "/public/csv/components-errors-{$date}.csv");
            $url = asset("storage/csv/components-errors-{$date}.csv");
            return redirect(route('components.index'))
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
        if (auth()->user()->cant('view', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'view']));
        }

        return view('ComponentsDir.show', ["component" => $component,]);
    }

    public function edit(Component $component)
    {
        if (auth()->user()->cant('update', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'edit']));
        }
        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('ComponentsDir.edit', [
            "component" => $component,
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
        ]);
    }

    public function newComment(Request $request, Component $component)
    {
        if (auth()->user()->cant('update', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'comment']));
        }else{
            $request->validate([
                "title" => "required|max:255",
                "comment" => "nullable",
            ]);

            $component->comment()->create(['title'=>$request->title, 'comment'=>$request->comment, 'user_id'=>auth()->user()->id]);
            return redirect(route('components.show', $component->id));
        }
    }

    public function update(Request $request, Component $component)
    {
        if (auth()->user()->cant('update', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'update']));
        }else{
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
            $component->fill($request->only(
                'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes'
            ))->save();
            $component->category()->sync($request->category);
            session()->flash('success_message', $component->name. ' has been updated successfully');
            return redirect(route("components.index"));
        }
    }

    public function destroy(Component $component)
    {
        if (auth()->user()->cant('delete', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'delete']));
        }else{
            $name = $component->name;
            $component->delete();
            session()->flash('danger_message', $name . ' was deleted from the system');

            return redirect(route('components.index'));
        }

    }

    public function export(Request $request)
    {
        if (auth()->user()->cant('viewAll', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Components', 'export']));
        }else{
            return \Maatwebsite\Excel\Facades\Excel::download(new ComponentsExport, 'components.csv');
        }
    }

    public function import(Request $request)
    {
        if (auth()->user()->cant('create', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Components', 'import']));
        }

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
    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAll', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Components', 'download pdf']));
        }
        $components = Component::withTrashed()->whereIn('id', json_decode($request->components))->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('ComponentsDir.pdf', compact('components'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        return $pdf->download("components-{$date}.pdf");
    }

    public function downloadShowPDF(Component $component)
    {
        if (auth()->user()->cant('view', $component)) {
            return redirect(route('errors.forbidden', ['components', $component->id, 'download pdf']));
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('ComponentsDir.showPdf', compact('component'));
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        return $pdf->download("component-{$component->id}-{$date}.pdf");
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if (auth()->user()->cant('viewAll', Component::class)) {
            return redirect(route('errors.forbidden', ['area', 'Components', 'recycle bin']));
        }
        if(auth()->user()->role_id == 1){
            $components = Component::onlyTrashed()->get();
            $locations = Location::all();
        }else{
            $assets = auth()->user()->location_components()->onlyTrashed();
            $locations = auth()->user()->locations;
        }
        return view('ComponentsDir.bin', ["components"=> $components,]);
    }

    public function restore($id)
    {
        $component = Component::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'restore']));
        }
        $name=$component->name;
        $component->restore();
        session()->flash('success_message', "#". $name . ' has been restored.');
        return redirect("/components");
    }

    public function forceDelete($id)
    {
        $component = Component::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $component)) {
            return redirect(route('errors.forbidden', ['component', $component->id, 'Force Delete']));
        }
        $name=$component->name;
        $component->forceDelete();
        session()->flash('danger_message', "Component - ". $name . ' was deleted permanently');
        return redirect("/component/bin");
    }
}
