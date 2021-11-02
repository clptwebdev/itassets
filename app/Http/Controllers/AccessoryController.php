<?php

namespace App\Http\Controllers;
use App\Exports\accessoryErrorsExport;
use App\Exports\accessoryExport;
use App\Imports\accessoryImport;
use App\Models\Accessory;
use App\Models\Category;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Depreciation;
use App\Models\Status;
use App\Models\Supplier;
use App\Rules\permittedLocation;
use App\Rules\findLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AccessoriesPdf;
use App\Jobs\AccessoryPdf;
use App\Models\Report;

class AccessoryController extends Controller
{
    public function index()
    {
        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessory', 'view']));
        }

        session(['orderby' => 'purchased_date']);
        session(['direction' => 'desc']);

        if(auth()->user()->role_id == 1){
            $accessories = Accessory::with('supplier', 'location')
                ->leftJoin('locations', 'locations.id', '=', 'accessories.location_id')
                ->leftJoin('manufacturers', 'manufacturers.id', '=', 'accessories.manufacturer_id')
                ->leftJoin('suppliers', 'suppliers.id', '=', 'accessories.supplier_id')
                ->orderBy(session('orderby') ?? 'purchased_date' , session('direction') ?? 'asc')
                ->paginate(intval(session('limit')) ?? 25, ['accessories.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name'])
                ->fragment('table');;
            $locations = Location::all();
        }else{
            $accessories = auth()->user()->location_accessories()
                ->leftJoin('locations', 'locations.id', '=', 'accessories.location_id')
                ->leftJoin('manufacturers', 'manufacturers.id', '=', 'accessories.manufacturer_id')
                ->leftJoin('suppliers', 'suppliers.id', '=', 'accessories.supplier_id')
                ->orderBy(session('orderby') ?? 'purchased_date' , session('direction') ?? 'asc')
                ->paginate(intval(session('limit')) ?? 25, ['accessories.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name'])
                ->fragment('table');
            $locations = auth()->user()->locations;
        }
        $this->clearFilter();
        $filter = 0;
        
        return view('accessory.view', [
            "accessories" => $accessories,
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => $locations,
            "filter" => 0,
        ]);
    }

    public function filter(Request $request)
    {
        if($request->isMethod('post')){

            if(! empty($request->search)){
                session(['search' => $request->search]);
            }else{
                $this->clearFilter();
            }

            if(! empty($request->limit)){
                session(['limit' => $request->limit]);
            }

            if(! empty($request->orderby)){
                $array = explode(' ', $request->orderby);
                if($array[0] != 'audit_date'){
                    session(['orderby' => $array[0]]);
                }else{
                    session(['orderby' => purchased_date]);
                }
                session(['direction' => $array[1]]);
            }

            if(! empty($request->locations)){
                session(['locations' => $request->locations]);
            }

            if(! empty($request->status))
            {
                session(['status' => $request->status]);
            }

            if(! empty($request->category))
            {  
                session(['category' => $request->category]);
            }

            if($request->start != '' && $request->end != '')
            { 
                session(['start' => $request->start]);
                session(['end' => $request->end]);
            }

            if($request->audit != 0)
            {  
                session(['audit' => $request->audit]);
            }

            if($request->warranty != 0)
            { 
                session(['warranty' => $request->warranty]);
            }

            session(['amount' => $request->amount]);
        }

        if(auth()->user()->role_id != 1){
            $locations = auth()->user()->locations->pluck('id');
            $locs = auth()->user()->locations;
            
        }else{
            $locations = \App\Models\Location::all()->pluck('id');
            $locs = \App\Models\Location::all();
        }
        
        
        $filter = 0;
        $accessories = Accessory::locationFilter($locations);
        if(session()->has('locations')) {
            $accessories->locationFilter(session('locations'));
            $filter++;
        }
        if(session()->has('status')) {
            $accessories->statusFilter(session('status'));
            $filter++;
        }
        if(session()->has('category')) {
            $accessories->categoryFilter(session('category'));
            $filter++;
        }
        if(session()->has('start') && session()->has('end')){
            $accessories->purchaseFilter(session('start'), session('end'));
            $filter++;
        }
        if(session()->has('amount')){
            $accessories->costFilter(session('amount'));
            $filter++;
        }

        if(session()->has('search')){
            $accessories->searchFilter(session('search'));
            $filter++;
        }
        
        $accessories->join('locations', 'accessories.location_id', '=', 'locations.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'accessories.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'accessories.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('assets.*','locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('limit') ?? 25;

        return view('accessory.view', [
            "accessories" => $accessories->paginate(intval($limit))->withPath(asset('/accessory/filter'))->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=> $locs,
            "filter" => $filter,
        ]);
    }

    public function clearFilter(){
        session()->forget(['locations', 'status', 'category', 'start', 'end', 'audit', 'warranty', 'amount', 'search']);
        return redirect(route('accessories.index'));
    }

    public function create()
    {
        if (auth()->user()->cant('create', Accessory::class)) {
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
            'depreciations' => Depreciation::all(),
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
            "model" => "nullable",
            "supplier_id" => "required",
            "location_id" => "required",
            "room" => "nullable",
            "notes" => "nullable",
            "status_id" => "nullable",
            'order_no' => 'nullable',
            'serial_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $accessory = Accessory::create(array_merge($request->only(
            'name', 'model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id', 'user_id'
        ), ['user_id' => auth()->user()->id]));
        $accessory->category()->attach($request->category);
        return redirect(route("accessories.index"));
    }

    public function importErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessories', 'export']));
        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new accessoryErrorsExport($export), "/public/csv/accessories-errors-{$date}.csv");
        $url = asset("storage/csv/accessories-errors-{$date}.csv");
        return redirect(route('accessories.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function ajaxMany(Request $request)
    {
        if($request->ajax()){
            $validation = Validator::make($request->all(), [
                "name.*" => "required|max:255",
                "model" => "nullable",
                'order_no.*' => 'nullable',
                'serial_no.*' => 'required',
                'warranty.*' => 'int',
                'location_id.*' => ['required', 'gt:0'],
                'room' => 'nullable',
                'purchased_date.*' => 'date',
                'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            ]);


            if($validation->fails()){
                return $validation->errors();
            }else{
                for($i = 0; $i < count($request->name); $i++)
                {
                    $accessory = new Accessory;
                    $accessory->name = $request->name[$i];
                    $accessory->model = $request->model[$i];
                    $accessory->serial_no = $request->serial_no[$i];
                    $accessory->status_id = $request->status_id[$i];
                    $accessory->purchased_date = \Carbon\Carbon::parse(str_replace('/','-',$request->purchased_date[$i]))->format("Y-m-d");
                    $accessory->purchased_cost = $request->purchased_cost[$i];
                    $accessory->donated = $request->donated[$i];
                    $accessory->supplier_id = $request->supplier_id[$i];
                    $accessory->manufacturer_id = $request->manufacturer_id[$i];
                    $accessory->order_no = $request->order_no[$i];
                    $accessory->warranty = $request->warranty[$i];
                    $accessory->location_id = $request->location_id[$i];
                    $accessory->room = $request->room[$i] ?? 'N/A';
                    $accessory->notes = $request->notes[$i];
                    $accessory->photo_id =  0;
                    $accessory->depreciation_id = $request->depreciation_id[$i];
                    $accessory->user_id = auth()->user()->id;
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
            'depreciations' => Depreciation::all(),
        ]);
    }

    public function update(Request $request, Accessory $accessory)
    {
        if (auth()->user()->cant('update', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'update']));
        }

        $request->validate([
            "name" => "required|max:255",
            "model" => "nullable",
            "supplier_id" => "required",
            "location_id" => "required",
            "room" => "nullable",
            "notes" => "nullable",
            'order_no' => 'nullable',
            'serial_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        if(isset($request->donated) && $request->donated == 1){ $donated = 1;}else{ $donated = 0;}
        $accessory->fill(array_merge($request->only(
            'name', 'model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ), ['donated' => $donated]))->save();
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
        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Accessory', 'export']));
        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new accessoryExport, "/public/csv/accessories-ex-{$date}.csv");
        $url = asset("storage/csv/accessories-ex-{$date}.csv");
        return redirect(route('accessories.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function import(Request $request)
    {
        if (auth()->user()->cant('viewAll', Accessory::class)) {
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
                    "locations"=> auth()->user()->locations,
                    "manufacturers"=>Manufacturer::all(),
                    "depreciations"=>Depreciation::all(),
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

        $accessories = array();
        $found = Accessory::withTrashed()->whereIn('id', json_decode($request->accessories))->get();
        foreach($found as $f){
            $array = array();
            $array['name'] = $f->name;
            $array['model'] = $f->model;
            $array['serial_no'] = $f->serial_no ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['room'] = $f->room ?? 'N/A';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['manufacturer'] = $f->manufacturer->name ?? 'N/A';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y');
            $array['purchased_cost'] = '£'.$f->purchased_cost;
            $array['donated'] = '£'.$f->donated;
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
            $array['warranty'] = $f->warranty;
            $array['status'] = $f->status->name;
            $array['color'] = $f->status->colour ?? '#666';
            $accessories[] = $array;
        }

        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'accessories-'.$date;

        dispatch(new AccessoriesPdf($accessories, $user, $path))->afterResponse();
        //Create Report
        
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('accessories.index'))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Accessory $accessory)
    {
        if (auth()->user()->cant('view', $accessory)) {
            return redirect(route('errors.forbidden', ['accessory', $accessory->id, 'view pdf']));
        }

        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'accessory-'.$accessory->id.'-'.$date;

        dispatch(new AccessoryPdf($accessory, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('accessories.show', $accessory->id))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
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

    public function changeStatus(Accessory $accessory, Request $request)
    {
        $accessory->status_id = $request->status;
        $accessory->save();
        session()->flash('success_message', $accessory->name . ' has had its status changed successfully');
        return redirect(route('accessories.show', $accessory->id));
    }

}
