<?php

namespace App\Http\Controllers;

use App\Exports\consumableExport;
use App\Exports\miscellaneaErrorsExport;
use App\Exports\miscellaneaExport;
use App\Exports\miscellaneousErrorsExport;
use App\Exports\miscellaneousExport;
use App\Imports\miscellaneaImport;
use App\Imports\miscellaneousImport;
use App\Models\Category;
use App\Models\Miscellanea;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Depreciation;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Jobs\MiscellaneousPdf;
use App\Jobs\MiscellaneaPdf;
use App\Models\Report;

class MiscellaneaController extends Controller
{
    
    public function newComment(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $miscellanea = Miscellanea::find($request->miscellanea_id);
        $miscellanea->comment()->create(['title'=>$request->title, 'comment'=>$request->comment, 'user_id'=>auth()->user()->id]);
        return redirect(route('miscellaneous.show', $miscellanea->id));
    }

    public function index()
    {
        if (auth()->user()->cant('viewAny', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'view']));
        }

        return view('miscellanea.view',[
            "miscellaneous"=>Miscellanea::all(),
        ]);
    }

    public function create()
    {
        if (auth()->user()->cant('create', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'create']));
        }

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('miscellanea.create', [
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
            'depreciations' => Depreciation::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'create']));
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

        $miscellanea = Miscellanea::create($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ));
        $miscellanea->category()->attach($request->category);
        return redirect(route("miscellaneous.index"));

    }

    public function importErrors(Request $request)
    {

        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        return \Maatwebsite\Excel\Facades\Excel::download(new miscellaneousErrorsExport($export), 'miscellaneousImportErrors.csv');
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
                    $miscellanea = new Miscellanea;
                    $miscellanea->name = $request->name[$i];
                    $miscellanea->serial_no = $request->serial_no[$i];
                    $miscellanea->status_id = $request->status_id[$i];
                    $miscellanea->purchased_date = \Carbon\Carbon::parse(str_replace('/','-',$request->purchased_date[$i]))->format("Y-m-d");
                    $miscellanea->purchased_cost = $request->purchased_cost[$i];
                    $miscellanea->supplier_id = $request->supplier_id[$i];
                    $miscellanea->manufacturer_id = $request->manufacturer_id[$i];
                    $miscellanea->order_no = $request->order_no[$i];
                    $miscellanea->warranty = $request->warranty[$i];
                    $miscellanea->depreciation_id = $request->depreciation_id[$i];
                    $miscellanea->location_id = $request->location_id[$i];
                    $miscellanea->room = $request->room[$i];
                    $miscellanea->notes = $request->notes[$i];
                    $miscellanea->photo_id =  0;

                    $miscellanea->save();
                }

                session()->flash('success_message', 'You have successfully added all the Miscellaneous items');
                return 'Success';
            }
        }

    }

    public function show(Miscellanea $miscellaneou)
    {
        if (auth()->user()->cant('create', $miscellaneou)) {
            return redirect(route('errors.forbidden', ['miscellaneous', $miscellaneou->id, 'view']));
        }
        return view('miscellanea.show', ["miscellaneou" => $miscellaneou]);
    }

    public function edit(Miscellanea $miscellaneou)
    {
        if (auth()->user()->cant('update', $miscellaneou)) {
            return redirect(route('errors.forbidden', ['miscellaneous', $miscellaneou->id, 'update']));
        }

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('miscellanea.edit', [
            "miscellanea" => $miscellaneou,
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            "categories" => Category::all(),
            'depreciations' => Depreciation::all(),
        ]);
    }

    public function update(Request $request, Miscellanea $miscellaneou)
    {
        dd($miscellaneou);
        if (auth()->user()->cant('update', $miscellanea)) {
            return redirect(route('errors.forbidden', ['miscellaneous', $miscellanea->id, 'update']));
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

        if(isset($request->donated) && $request->donated == 1){ $donated = 1;}else{ $donated = 0;}
        $miscellanea->fill(array_merge($request->only(
            'name', 'model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ), ['donated' => $donated]))->save();
        $miscellanea->category()->sync($request->category);
        session()->flash('success_message', $miscellanea->name. ' has been updated successfully');

        return redirect(route("miscellaneous.index"));
    }

    public function destroy(Miscellanea $miscellaneou)
    {
        if (auth()->user()->cant('delete', $miscellaneou)) {
            return redirect(route('errors.forbidden', ['miscellaneous', $miscellaneou->id, 'delete']));
        }
        $name = $miscellaneou->name;
        $miscellaneou->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return redirect(route('miscellaneous.index'));

    }

    public function export(Miscellanea $miscellanea)
    {
        if (auth()->user()->cant('export', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'export']));
        }
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new miscellaneousExport, "/public/csv/miscellaneous-ex-{$date}.csv");
        $url = asset("storage/csv/miscellaneous-ex-{$date}.csv");
        return redirect(route('miscellaneous.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function import(Request $request)
    {
        if (auth()->user()->cant('create', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'import']));
        }

        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());


        if(in_array($result[0],$extensions)){
            $path = $request->file("csv")->getRealPath();
            $import = new miscellaneousImport;
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


                return view('miscellanea.import-errors', [
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

                return redirect('/miscellaneous')->with('success_message', 'All miscellaneous were added correctly!');

            }
        }else{
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return redirect(route('miscellaneous.index'));
        }



    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAny', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'export pdf']));
        }
        
        $miscellaneous = array();

        $found = Miscellanea::withTrashed()->whereIn('id', json_decode($request->miscellaneous))->get();
        foreach($found as $f){
            $array = array();
            $array['name'] = $f->name;
            $array['serial_no'] = $f->serial_no ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['room'] = $f->room ?? 'Unallocated';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['manufacturer'] = $f->manufacturer->name ?? 'N/A';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y');
            $array['purchased_cost'] = '£'.$f->purchased_cost;
            $eol = \Carbon\Carbon::parse($f->purchased_date)->addYears($f->depreciation->years);
            if($f->depreciation->exists()){
                if($eol->isPast()){
                    $dep = 0;
                }else{
    
                    $age = \Carbon\Carbon::now()->floatDiffInYears($f->purchased_date);
                    $percent = 100 / $f->depreciation->years;
                    $percentage = floor($age)*$percent;
                    $dep = $f->purchased_cost * ((100 - $percentage) / 100);
                }
            }
            $array['depreciation'] = $dep;
            $array['supplier'] = $f->supplier->name ?? 'N/A';
            $array['warranty'] = $f->warranty ?? '0';
            $array['status'] = $f->status->name ?? 'N/A';
            $array['color'] = $f->status->colour ?? '#666';
            $miscellaneous[] = $array;
        }

        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'miscellaneous-'.$date;

        dispatch(new MiscellaneousPdf($miscellaneous, $user, $path))->afterResponse();
        //Create Report
        
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('miscellaneous.index'))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Miscellanea $miscellanea)
    {
        if (auth()->user()->cant('view', $miscellanea)) {
            return redirect(route('errors.forbidden', ['miscellaneous', $miscellanea->id, 'export pdf']));
        }

        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'miscellanea-'.$miscellanea->id.'-'.$date;

        dispatch(new MiscellaneaPdf($miscellanea, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('miscellaneous.show', $miscellanea->id))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if (auth()->user()->cant('recycleBin', Miscellanea::class)) {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'recycle bin']));
        }

        if(auth()->user()->role_id == 1){
            $miscellaneous = Miscellanea::onlyTrashed()->get();
        }else{
            $miscellaneous = auth()->user()->location_miscellaneous()->onlyTrashed();
        }
        return view('miscellanea.bin', compact('miscellaneous'));
    }

    public function restore($id)
    {
        $miscellanea = Miscellanea::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $miscellanea)) {
            return redirect(route('errors.forbidden', ['miscellanea', $miscellanea->id, 'restore']));
        }
        $miscellanea->restore();
        session()->flash('success_message', "#". $miscellanea->name . ' has been restored.');
        return redirect("/miscellaneous");
    }

    public function forceDelete($id)
    {
        $miscellanea = Miscellanea::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $miscellanea)) {
            return redirect(route('errors.forbidden', ['miscellanea', $miscellanea->id, 'remove']));
        }
        $name=$miscellanea->name;
        $miscellanea->forceDelete();
        session()->flash('danger_message', "miscellanea - ". $name . ' was deleted permanently');
        return redirect("/miscellanea/bin");
    }

    public function changeStatus(Miscellanea $miscellanea, Request $request)
    {
        $miscellanea->status_id = $request->status;
        $miscellanea->save();
        session()->flash('success_message', $miscellanea->name . ' has had its status changed successfully');
        return redirect(route('miscellaneous.show', $miscellanea->id));
    }
}
