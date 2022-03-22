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
use PHPUnit\Util\Test;
use App\Rules\checkAssetTag;

class AccessoryController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Accessories.');
        }
        session(['orderby' => 'purchased_date']);
        session(['direction' => 'desc']);

        $accessories = Accessory::locationFilter(auth()->user()->locations->pluck('id'))
            ->leftJoin('locations', 'locations.id', '=', 'accessories.location_id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'accessories.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'accessories.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->paginate(intval(session('limit')) ?? 25, ['accessories.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name'])
            ->fragment('table');

        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('accessories')->get();

        $this->clearFilter();
        $filter = 0;

        $categories = Category::with('accessories')->select('id', 'name')->get();
        $statuses = Status::select('id', 'name', 'deployable')->withCount('accessories')->get();

        return view('accessory.view', [
            "accessories" => $accessories,
            'suppliers' => Supplier::all(),
            'statuses' => $statuses,
            'categories' => $categories,
            "locations" => $locations,
            "filter" => 0,
        ]);
    }

    public function filter(Request $request)
    {
        if($request->isMethod('post'))
        {

            if(! empty($request->search))
            {
                session(['search' => $request->search]);
            } else
            {
                $this->clearFilter();
            }

            if(! empty($request->limit))
            {
                session(['limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);
                if($array[0] != 'audit_date')
                {
                    session(['orderby' => $array[0]]);
                } else
                {
                    session(['orderby' => purchased_date]);
                }
                session(['direction' => $array[1]]);
            }

            if(! empty($request->locations))
            {
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

            session(['assets_min' => $request->minCost]);
            session(['assets_max' => $request->maxCost]);
        }

        $locations = auth()->user()->locations->pluck('id');
        $locs = auth()->user()->locations;

        $filter = 0;
        $accessories = Accessory::locationFilter($locations);
        if(session()->has('locations'))
        {
            $accessories->locationFilter(session('locations'));
            $filter++;
        }
        if(session()->has('status'))
        {
            $accessories->statusFilter(session('status'));
            $filter++;
        }
        if(session()->has('category'))
        {
            $accessories->categoryFilter(session('category'));
            $filter++;
        }
        if(session()->has('start') && session()->has('end'))
        {
            $accessories->purchaseFilter(session('start'), session('end'));
            $filter++;
        }
        if(session()->has('assets_min') && session()->has('assets_max'))
        {
            $accessories->costFilter(session('assets_min'), session('assets_max'));
            $filter++;
        }

        if(session()->has('search'))
        {
            $accessories->searchFilter(session('search'));
            $filter++;
        }

        $accessories->join('locations', 'accessories.location_id', '=', 'locations.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'accessories.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'accessories.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('accessories.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('limit') ?? 25;

        return view('accessory.view', [
            "accessories" => $accessories->paginate(intval($limit))->withPath('/accessory/filter')->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => $locs,
            "filter" => $filter,
        ]);
    }

    public function clearFilter()
    {
        session()->forget(['locations', 'status', 'category', 'start', 'end', 'audit', 'warranty', 'amount', 'search']);

        return to_route('accessories.index');
    }

    public function create()
    {
        if(auth()->user()->cant('create', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Create Accessories.');
        }
        $locations = auth()->user()->locations;

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
        $accessory->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);

        return to_route('accessories.show', $accessory->id);
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Create Accessories.');

        }

        $request->validate([
            "name" => "required|max:255",
            "asset_tag" => ['sometimes', 'nullable', new checkAssetTag($request['location_id'])],
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
            'name', 'asset_tag', 'model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id', 'user_id'
        ), ['user_id' => auth()->user()->id]));
        $accessory->category()->attach(explode(',', $request->category));

        return to_route("accessories.index")->with('success_message', $request->name . 'has been successfully created!');
    }

    public function importErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Export Accessories.');

        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new accessoryErrorsExport($export), "/public/csv/accessories-errors-{$date}.csv");
        $url = asset("storage/csv/accessories-errors-{$date}.csv");

        return to_route('accessories.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function ajaxMany(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "asset_tag" => ['sometimes', 'nullable'],
            'purchased_cost.*' => 'required',
            'purchased_date.*' => 'date',
            'location_id.*' => 'required',
        ]);
        if($validation->fails())
        {
            return $validation->errors();
        } else
        {

            for($i = 0; $i < count($request->name); $i++)
            {

                $accessory = new Accessory;

                $location = Location::find($request->location_id[$i]);
                if($request->name[$i] != '')
                {
                    $name = $request->name[$i];
                } else
                {
                    $request->asset_tag[$i] != '' ? $tag = $request->asset_tag[$i] : $tag = '1234';
                    $name = strtoupper(substr($location->name ?? 'UN', 0, 1)) . "-{$tag}";
                }
                $accessory->name = $name;

                $accessory->asset_tag = $tag;
                $accessory->model = $request->model[$i];
                //Serial No Cannot be ""
                //If the imported Serial Number is empty assign it to "0"
                $request->serial_no[$i] != '' ? $accessory->serial_no = $request->serial_no[$i] : $accessory->serial_no = "-";

                $accessory->status_id = $request->status_id[$i];
                $accessory->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $accessory->purchased_cost = $request->purchased_cost[$i];
                $accessory->donated = $request->donated[$i];
                $accessory->supplier_id = $request->supplier_id[$i];
                $accessory->manufacturer_id = $request->manufacturer_id[$i];
                $accessory->order_no = $request->order_no[$i];
                $accessory->warranty = $request->warranty[$i];
                $accessory->location_id = $request->location_id[$i];
                $accessory->room = $request->room[$i] ?? 'N/A';
                $accessory->notes = $request->notes[$i];
                $accessory->photo_id = 0;
                $accessory->depreciation_id = $request->depreciation_id[$i];
                $accessory->user_id = auth()->user()->id;
                $accessory->save();
            }

            session()->flash('success_message', 'You have successfully added all Accessories!');

            return 'Success';
        }
    }

    public function show(Accessory $accessory)
    {
        if(auth()->user()->cant('view', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Show Accessory.');
        }

        return view('accessory.show', [
            "accessory" => $accessory,
            'locations' => Location::all(),
        ]);
    }

    public function edit(Accessory $accessory)
    {
        if(auth()->user()->cant('update', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Edit Accessory.');

        }

        $locations = auth()->user()->locations;

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
        if(auth()->user()->cant('update', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Update Accessories.');

        }

        $request->validate([
            "name" => "required|max:255",
            "asset_tag" => ['sometimes', 'nullable'],
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
        if(isset($request->donated) && $request->donated == 1)
        {
            $donated = 1;
        } else
        {
            $donated = 0;
        }
        $accessory->fill(array_merge($request->only(
            'name', 'asset_tag', 'model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ), ['donated' => $donated]))->save();
        session()->flash('success_message', $accessory->name . ' has been Updated successfully');
        if(! empty($request->category))
        {
            $accessory->category()->sync(explode(',', $request->category));
        }

        return to_route("accessories.index");
    }

    public function destroy(Accessory $accessory)
    {
        if(auth()->user()->cant('delete', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Delete Accessories.');

        }

        $name = $accessory->name;
        $accessory->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return to_route('accessories.index');
    }

    public function export(Accessory $accessory)
    {
        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Export Accessories.');
        }

        $accessory = Accessory::locationFilter(auth()->user()->locations->pluck('id'))->get();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new accessoryExport($accessory), "/public/csv/accessories-ex-{$date}.xlsx");
        $url = asset("storage/csv/accessories-ex-{$date}.xlsx");

        return to_route('accessories.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Import Accessories.');

        }
        $extensions = array("csv");
        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
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
                    } else
                    {
                        $array = [];
                    }

                    foreach($error['errors'] as $e)
                    {
                        $array[$error['attributes']] = $e;
                    }
                    $errorValues[$error['row']] = $array;

                }

                return view('accessory.import-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "statuses" => Status::all(),
                    "suppliers" => Supplier::all(),
                    "locations" => auth()->user()->locations,
                    "manufacturers" => Manufacturer::all(),
                    "depreciations" => Depreciation::all(),
                ]);

            } else
            {
                return to_route('accessories.index')->with('success_message', 'All Accessories were added correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('accessories.index');
        }


    }

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Download Accessories.');

        }

        $accessories = array();
        $found = Accessory::withTrashed()->whereIn('id', json_decode($request->accessories))->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name;
            $array['model'] = $f->model;
            $array['serial_no'] = $f->serial_no ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['room'] = $f->room ?? 'N/A';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['manufacturer'] = $f->manufacturer->name ?? 'N/A';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y');
            $array['purchased_cost'] = '£' . $f->purchased_cost;
            $array['donated'] = '£' . $f->donated;
            $eol = \Carbon\Carbon::parse($f->purchased_date)->addYears($f->depreciation->years);
            if($f->depreciation->exists())
            {
                if($eol->isPast())
                {
                    $dep = 0;
                } else
                {

                    $age = \Carbon\Carbon::now()->floatDiffInYears($f->purchased_date);
                    $percent = 100 / $f->depreciation->years;
                    $percentage = floor($age) * $percent;
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
        $path = 'accessories-' . $date;

        dispatch(new AccessoriesPdf($accessories, $user, $path))->afterResponse();
        //Create Report

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('accessories.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Accessory $accessory)
    {
        if(auth()->user()->cant('view', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Download Accessories.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'accessory-' . $accessory->id . '-' . $date;

        dispatch(new AccessoryPdf($accessory, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('accessories.show', $accessory->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to View Archived Accessories.');

        }
        $accessories = auth()->user()->location_accessories()->onlyTrashed()->get();

        return view('accessory.bin', compact('accessories'));
    }

    public function restore($id)
    {
        $accessory = Accessory::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Restore Accessory.');
        }
        $accessory->restore();
        session()->flash('success_message', "#" . $accessory->name . ' has been restored.');

        return to_route("accessories.index");
    }

    public function forceDelete($id)
    {
        $accessory = Accessory::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('forceDelete', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Delete Accessory.');

        }
        $name = $accessory->name;
        $accessory->forceDelete();
        session()->flash('danger_message', "Accessory - " . $name . ' was deleted permanently');

        return to_route('accessories.bin');
    }

    public function changeStatus(Accessory $accessory, Request $request)
    {
        if(auth()->user()->cant('update', Status::class))
        {
            return ErrorController::forbidden(to_route('accessories.show', $accessory->id), 'Unauthorised to Change Statuses Accessory.');
        }
        $accessory->status_id = $request->status;
        $accessory->save();
        session()->flash('success_message', $accessory->name . ' has had its status changed successfully');

        return to_route('accessories.show', $accessory->id);
    }

}
