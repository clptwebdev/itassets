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
use App\Jobs\ConsumablesPdf;
use App\Jobs\ConsumablePdf;
use App\Models\Report;

class ConsumableController extends Controller {

    public function newComment(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $consumable = Consumable::whereId($request->consumables_id)->first();
        $consumable->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);

        return to_route('consumables.show', $consumable->id);
    }

    public function index()
    {
        if(auth()->user()->cant('viewAll', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to View Consumables.');

        }
        $consumables = Consumable::LocationFilter(auth()->user()->locations->pluck('id'))->leftJoin('locations', 'locations.id', '=', 'consumables.location_id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'consumables.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'consumables.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->paginate(intval(session('limit')) ?? 25, ['consumables.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name'])
            ->fragment('table');

        return view('consumable.view', compact('consumables'));
    }

    public function create()
    {
        if(auth()->user()->cant('create', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Create Consumables.');

        }
        $locations = auth()->user()->locations;

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
        if(auth()->user()->cant('create', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Store Consumables.');

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

        return to_route("consumables.index");

    }

    public function importErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if(auth()->user()->cant('viewAll', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Export Consumables.');

        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new consumableErrorsExport($export), "/public/csv/consumables-errors-{$date}.csv");
        $url = asset("storage/csv/consumables-errors-{$date}.csv");

        return to_route('consumables.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
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
                $consumable = new Consumable;
                $consumable->name = $request->name[$i];
                $consumable->serial_no = $request->serial_no[$i];
                $consumable->status_id = $request->status_id[$i];
                $consumable->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $consumable->purchased_cost = floatval($request->purchased_cost[$i]);
                $consumable->supplier_id = $request->supplier_id[$i];
                $consumable->manufacturer_id = $request->manufacturer_id[$i];
                $consumable->order_no = $request->order_no[$i];
                $consumable->warranty = $request->warranty[$i];
                $consumable->location_id = $request->location_id[$i];
                $consumable->notes = $request->notes[$i];
                $consumable->photo_id = 0;

                $consumable->save();
            }

            session()->flash('success_message', 'You have successfully added all Consumables!');

            return 'Success';
        }
    }

    public function show(Consumable $consumable)
    {
        if(auth()->user()->cant('create', $consumable))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Show Consumables.');

        }

        return view('consumable.show', ["consumable" => $consumable]);
    }

    public function edit(Consumable $consumable)
    {
        if(auth()->user()->cant('update', $consumable))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Update Consumables.');

        }

        $locations = auth()->user()->locations;

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
        if(auth()->user()->cant('update', $consumable))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Update Consumables.');

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
        session()->flash('success_message', $consumable->name . ' has been updated successfully');

        return to_route("consumables.index");
    }

    public function destroy(Consumable $consumable)
    {
        if(auth()->user()->cant('delete', $consumable))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Delete Consumables.');

        }
        $name = $consumable->name;
        $consumable->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return to_route('consumables.index');

    }

    public function export(Consumable $consumable)
    {
        if(auth()->user()->cant('export', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Export Consumables.');

        }
        $consumables = Consumable::all();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new consumableExport($consumables), "/public/csv/consumables-ex-{$date}.xlsx");
        $url = asset("storage/csv/consumables-ex-{$date}.xlsx");

        return to_route('consumables.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();


    }

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Import Consumables.');

        }

        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
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

                return view('consumable.import-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "statuses" => Status::all(),
                    "suppliers" => Supplier::all(),
                    "locations" => Location::all(),
                    "manufacturers" => Manufacturer::all(),
                ]);

            } else
            {

                return to_route('consumables.index')->with('success_message', 'All Consumables were added correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('consumables.index');
        }


    }

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Download Consumables.');

        }

        $consumables = array();
        $found = Consumable::withTrashed()->whereIn('id', json_decode($request->consumables))->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name;
            $array['serial_no'] = $f->serial_no ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['manufacturer'] = $f->manufacturer->name ?? 'N/A';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y');
            $array['purchased_cost'] = '£' . $f->purchased_cost;
            $array['supplier'] = $f->supplier->name ?? 'N/A';
            $array['warranty'] = $f->warranty ?? '0';
            $array['status'] = $f->status->name ?? 'N/A';
            $array['color'] = $f->status->colour ?? '#666';
            $consumables[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'consumables-' . $date;

        dispatch(new ConsumablesPdf($consumables, $user, $path))->afterResponse();
        //Create Report

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('consumables.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Consumable $consumable)
    {
        if(auth()->user()->cant('export', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Download Consumables.');

        }
        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'consumable-' . $consumable->id . '-' . $date;

        dispatch(new ConsumablePdf($consumable, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('consumables.show', $consumable->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if(auth()->user()->cant('recycleBin', Consumable::class))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Recycle Consumables.');

        }

        $consumables = auth()->user()->location_consumables()->onlyTrashed()->paginate();

        return view('consumable.bin', compact('consumables'));
    }

    public function restore($id)
    {
        $consumable = Consumable::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $consumable))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Restore Consumables.');

        }
        $consumable->restore();
        session()->flash('success_message', "#" . $consumable->name . ' has been restored.');

        return to_route("components.index");
    }

    public function forceDelete($id)
    {
        $consumable = Consumable::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $consumable))
        {
            return ErrorController::forbidden(to_route('consumables.index'), 'Unauthorised to Delete Consumables.');

        }
        $name = $consumable->name;
        $consumable->forceDelete();
        session()->flash('danger_message', "Consumable - " . $name . ' was deleted permanently');

        return to_route("consumables.bin");
    }

    public function changeStatus(Consumable $consumable, Request $request)
    {
        if(auth()->user()->cant('update', Status::class))
        {
            return ErrorController::forbidden(to_route('accessories.show', $consumable->id), 'Unauthorised to Change Statuses Consumables.');
        }
        $consumable->status_id = $request->status;
        $consumable->save();
        session()->flash('success_message', $consumable->name . ' has had its status changed successfully');

        return to_route('consumables.show', $consumable->id);
    }

}
