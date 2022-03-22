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
use App\Models\Comment;
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

class MiscellaneaController extends Controller {

    public function newComment(Request $request)
    {
        if(auth()->user()->cant('comment', Comment::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Create Comments for Miscellaneous.');

        }
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $miscellanea = Miscellanea::find($request->miscellanea_id);
        $miscellanea->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);

        return to_route('miscellaneous.show', $miscellanea->id);
    }

    public function index()
    {
        if(auth()->user()->cant('viewAny', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Miscellaneous.');

        }

        session(['orderby' => 'purchased_date']);
        session(['direction' => 'desc']);

        $miscellaneous = Miscellanea::locationFilter(auth()->user()->locations->pluck('id'))
            ->leftJoin('locations', 'locations.id', '=', 'miscellaneas.location_id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'miscellaneas.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'miscellaneas.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->paginate(intval(session('limit')) ?? 25, ['miscellaneas.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name'])
            ->fragment('table');
        $locations = auth()->user()->locations;

        $this->clearFilter();
        $filter = 0;

        return view('miscellanea.view', [
            "miscellaneous" => $miscellaneous,
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => $locations,
            "filter" => 0,
        ]);

//        if (auth()->user()->cant('viewAny', Miscellanea::class)) {
//            return redirect(to_route('errors.forbidden', ['area', 'miscellaneous', 'view']));
//        }
//
//        return view('miscellanea.view',[
//            "miscellaneous"=>Miscellanea::all(),
//        ]);
    }

    public function create()
    {
        if(auth()->user()->cant('create', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Create Miscellaneous.');

        }

        $locations = auth()->user()->locations;

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
        if(auth()->user()->cant('create', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Store Miscellaneous.');

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

        return to_route("miscellaneous.index");

    }

    public function clearFilter()
    {
        session()->forget(['locations', 'status', 'category', 'start', 'end', 'audit', 'warranty', 'amount', 'search']);

        return to_route('miscellaneous.index');
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

        $locations = \App\Models\Location::all()->pluck('id');
        $locs = \App\Models\Location::all();

        $filter = 0;

        $miscellaneous = Miscellanea::locationFilter($locations);
        if(session()->has('locations'))
        {
            $miscellaneous->locationFilter(session('locations'));
            $filter++;
        }
        if(session()->has('status'))
        {
            $miscellaneous->statusFilter(session('status'));
            $filter++;
        }
        if(session()->has('category'))
        {
            $miscellaneous->categoryFilter(session('category'));
            $filter++;
        }
        if(session()->has('start') && session()->has('end'))
        {
            $miscellaneous->purchaseFilter(session('start'), session('end'));
            $filter++;
        }
        if(session()->has('assets_min') && session()->has('assets_max'))
        {
            $miscellaneous->costFilter(session('assets_min'), session('assets_max'));
            $filter++;
        }

        if(session()->has('search'))
        {
            $miscellaneous->searchFilter(session('search'));
            $filter++;
        }

        $miscellaneous->join('locations', 'miscellaneas.location_id', '=', 'locations.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'miscellaneas.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'miscellaneas.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('miscellaneas.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('limit') ?? 25;

        return view('miscellanea.view', [
            "miscellaneous" => $miscellaneous->paginate(intval($limit))->withPath('/miscellanea/filter')->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => $locs,
            "filter" => $filter,
        ]);
    }

    public function importErrors(Request $request)
    {
        if(auth()->user()->cant('viewAny', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Export Miscellaneous.');

        }
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        return \Maatwebsite\Excel\Facades\Excel::download(new miscellaneousErrorsExport($export), 'miscellaneousImportErrors.csv');
    }

    public function ajaxMany(Request $request)
    {

        $validation = Validator::make($request->all(), [
            "name.*" => "required|max:255",
            'order_no.*' => 'required',
            'serial_no.*' => 'required',
            'warranty.*' => 'int',
            'location_id.*' => 'required|gt:0',
            'purchased_date.*' => 'nullable|date',
            'purchased_cost.*' => 'required',
        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                $miscellanea = new Miscellanea;
                $miscellanea->name = $request->name[$i];
                $miscellanea->serial_no = $request->serial_no[$i];
                $miscellanea->status_id = $request->status_id[$i];
                $miscellanea->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $miscellanea->purchased_cost = floatval($request->purchased_cost[$i]);
                $miscellanea->supplier_id = $request->supplier_id[$i];
                $miscellanea->manufacturer_id = $request->manufacturer_id[$i];
                $miscellanea->order_no = $request->order_no[$i];
                $miscellanea->warranty = $request->warranty[$i];
                $miscellanea->depreciation_id = $request->depreciation_id[$i];
                $miscellanea->location_id = $request->location_id[$i];
                $miscellanea->room = $request->room[$i];
                $miscellanea->notes = $request->notes[$i];
                $miscellanea->photo_id = 0;

                $miscellanea->save();
            }

            session()->flash('success_message', 'You have successfully added all the Miscellaneous items');

            return 'Success';
        }
    }

    public function show(Miscellanea $miscellaneou)
    {
        if(auth()->user()->cant('create', $miscellaneou))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Show Miscellaneous.');

        }

        return view('miscellanea.show', ["miscellaneou" => $miscellaneou]);
    }

    public function edit(Miscellanea $miscellaneou)
    {
        if(auth()->user()->cant('update', $miscellaneou))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Edit Miscellaneous.');

        }

        $locations = auth()->user()->locations;

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
        if(auth()->user()->cant('update', $miscellaneou))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Update Miscellaneous.');

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

        if(isset($request->donated) && $request->donated == 1)
        {
            $donated = 1;
        } else
        {
            $donated = 0;
        }
        $miscellaneou->fill(array_merge($request->only(
            'name', 'model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ), ['donated' => $donated]))->save();
        $miscellaneou->category()->sync($request->category);
        session()->flash('success_message', $miscellaneou->name . ' has been updated successfully');

        return to_route("miscellaneous.index");
    }

    public function destroy(Miscellanea $miscellaneou)
    {
        if(auth()->user()->cant('delete', $miscellaneou))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Delete Miscellaneous.');

        }
        $name = $miscellaneou->name;
        $miscellaneou->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return to_route('miscellaneous.index');

    }

    public function export(Miscellanea $miscellanea)
    {
        if(auth()->user()->cant('export', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Export Miscellaneous.');

        }
        $miscellaneous = Miscellanea::all();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new miscellaneousExport($miscellaneous), "/public/csv/miscellaneous-ex-{$date}.xlsx");
        $url = asset("storage/csv/miscellaneous-ex-{$date}.xlsx");

        return to_route('miscellaneous.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to import Miscellaneous.');

        }

        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
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

                return view('miscellanea.import-errors', [
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

                return to_route('miscellaneous.index')->with('success_message', 'All miscellaneous were added correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('miscellaneous.index');
        }


    }

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAny', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Export Miscellaneous.');

        }

        $miscellaneous = array();

        $found = Miscellanea::withTrashed()->whereIn('id', json_decode($request->miscellaneous))->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name;
            $array['serial_no'] = $f->serial_no ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['room'] = $f->room ?? 'Unallocated';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['manufacturer'] = $f->manufacturer->name ?? 'N/A';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y');
            $array['purchased_cost'] = '£' . $f->purchased_cost;
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
            $array['warranty'] = $f->warranty ?? '0';
            $array['status'] = $f->status->name ?? 'N/A';
            $array['color'] = $f->status->colour ?? '#666';
            $miscellaneous[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'miscellaneous-' . $date;

        dispatch(new MiscellaneousPdf($miscellaneous, $user, $path))->afterResponse();
        //Create Report

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('miscellaneous.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Miscellanea $miscellanea)
    {
        if(auth()->user()->cant('view', $miscellanea))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Download Miscellaneous.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'miscellanea-' . $miscellanea->id . '-' . $date;

        dispatch(new MiscellaneaPdf($miscellanea, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('miscellaneous.show', $miscellanea->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if(auth()->user()->cant('recycleBin', Miscellanea::class))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Recycle Miscellaneous.');

        }

        $miscellaneous = auth()->user()->location_miscellaneous()->onlyTrashed()->paginate();

        return view('miscellanea.bin', compact('miscellaneous'));
    }

    public function restore($id)
    {
        $miscellanea = Miscellanea::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $miscellanea))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Restore Miscellaneous.');

        }
        $miscellanea->restore();
        session()->flash('success_message', "#" . $miscellanea->name . ' has been restored.');

        return to_route("miscellaneous.index");
    }

    public function forceDelete($id)
    {
        $miscellanea = Miscellanea::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $miscellanea))
        {
            return ErrorController::forbidden(to_route('miscellaneous.index'), 'Unauthorised to Delete Miscellaneous.');

        }
        $name = $miscellanea->name;
        $miscellanea->forceDelete();
        session()->flash('danger_message', "miscellanea - " . $name . ' was deleted permanently');

        return to_route("miscellaneous.bin");
    }

    public function changeStatus(Miscellanea $miscellanea, Request $request)
    {
        if(auth()->user()->cant('update', Status::class))
        {
            return ErrorController::forbidden(to_route('accessories.show', $miscellanea->id), 'Unauthorised to Change Statuses Miscellaneous.');
        }
        $miscellanea->status_id = $request->status;
        $miscellanea->save();
        session()->flash('success_message', $miscellanea->name . ' has had its status changed successfully');

        return to_route('miscellaneous.show', $miscellanea->id);
    }

}
