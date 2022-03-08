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
use Illuminate\Support\Facades\Storage;
use App\Jobs\ComponentsPdf;
use App\Jobs\ComponentPdf;
use App\Models\Report;

class ComponentController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', Component::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Components.');

        }

        session(['orderby' => 'purchased_date']);
        session(['direction' => 'desc']);
        $components = Component::locationFilter(auth()->user()->locations->pluck('id'))
            ->leftJoin('locations', 'locations.id', '=', 'components.location_id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'components.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'components.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->paginate(intval(session('limit')) ?? 25, ['components.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name'])
            ->fragment('table');
        $locations = auth()->user()->locations;

        $this->clearFilter();

        $filter = 0;
        $categories = Category::with('components')->select('id', 'name')->get();
        $statuses = Status::select('id', 'name', 'deployable')->withCount('components')->get();

        return view('ComponentsDir.view', [
            "components" => $components,
            'suppliers' => Supplier::all(),
            'statuses' => $statuses,
            'categories' => $categories,
            "locations" => $locations,
            "filter" => 0,
        ]);
    }

    public function clearFilter()
    {
        session()->forget(['locations', 'status', 'category', 'start', 'end', 'audit', 'warranty', 'amount', 'search']);

        return to_route('components.index');
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

            session(['amount' => $request->amount]);
        }

        $locations = \App\Models\Location::all()->pluck('id');
        $locs = \App\Models\Location::all();

        $filter = 0;

        $components = Component::locationFilter($locations);
        if(session()->has('locations'))
        {
            $components->locationFilter(session('locations'));
            $filter++;
        }
        if(session()->has('status'))
        {
            $components->statusFilter(session('status'));
            $filter++;
        }
        if(session()->has('category'))
        {
            $components->categoryFilter(session('category'));
            $filter++;
        }
        if(session()->has('start') && session()->has('end'))
        {
            $components->purchaseFilter(session('start'), session('end'));
            $filter++;
        }
        if(session()->has('amount'))
        {
            $components->costFilter(session('amount'));
            $filter++;
        }

        if(session()->has('search'))
        {
            $components->searchFilter(session('search'));
            $filter++;
        }

        $components->join('locations', 'components.location_id', '=', 'locations.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'components.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'components.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('components.*', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('limit') ?? 25;

        return view('ComponentsDir.view', [
            "components" => $components->paginate(intval($limit))->withPath('/component/filter')->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => $locs,
            "filter" => $filter,
        ]);
    }

    public function create()
    {
        if(auth()->user()->cant('create', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Create Components.');

        }
        $locations = auth()->user()->locations;

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
        if(auth()->user()->cant('create', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Store Components.');

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
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes', 'photo_id'
        ));
        $component->category()->attach(explode(',', $request->category));

        return to_route("components.index")->with('success_message', $request->name . ' Has been successfully added!');
    }

    public function importErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if(auth()->user()->cant('viewAll', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Export Components.');

        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new componentErrorsExport($export), "/public/csv/components-errors-{$date}.csv");
        $url = asset("storage/csv/components-errors-{$date}.csv");

        return to_route('components.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function ajaxMany(Request $request)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                "name.*" => "required|max:255",
                'order_no.*' => 'required',
                'serial_no.*' => 'required',
                'warranty.*' => 'int',
                'location_id.*' => 'required|gt:0',
                'purchased_date.*' => 'nullable|date',
                'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',

            ]);

            if($validation->fails())
            {
                return $validation->errors();
            } else
            {
                for($i = 0; $i < count($request->name); $i++)
                {
                    $component = new Component;
                    $component->name = $request->name[$i];
                    $component->serial_no = $request->serial_no[$i];
                    $component->status_id = $request->status_id[$i];
                    $component->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                    $component->purchased_cost = $request->purchased_cost[$i];
                    $component->supplier_id = $request->supplier_id[$i];
                    $component->manufacturer_id = $request->manufacturer_id[$i];
                    $component->order_no = $request->order_no[$i];
                    $component->warranty = $request->warranty[$i];
                    $component->location_id = $request->location_id[$i];
                    $component->notes = $request->notes[$i];
                    $component->photo_id = 0;
                    $component->save();
                }

                session()->flash('success_message', 'You have successfully added all Components!');

                return 'Success';
            }
        }
    }

    public function show(Component $component)
    {
        if(auth()->user()->cant('view', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Show Components.');

        }

        return view('ComponentsDir.show', ["data" => $component,]);
    }

    public function edit(Component $component)
    {
        if(auth()->user()->cant('update', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Update Components.');
        }

        $locations = auth()->user()->locations;

        return view('ComponentsDir.edit', [
            "data" => $component,
            "locations" => $locations,
            "statuses" => Status::all(),
            "suppliers" => Supplier::all(),
            "manufacturers" => Manufacturer::all(),
            'categories' => Category::all(),
        ]);
    }

    public function newComment(Request $request, Component $component)
    {
        if(auth()->user()->cant('update', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Comment on Components.');

        } else
        {
            $request->validate([
                "title" => "required|max:255",
                "comment" => "nullable",
            ]);

            $component->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);

            return to_route('components.show', $component->id);
        }
    }

    public function update(Request $request, Component $component)
    {
        if(auth()->user()->cant('update', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Update Components.');

        } else
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
            $component->fill($request->only(
                'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'manufacturer_id', 'notes', 'photo_id'
            ))->save();
            $component->category()->sync(explode(',', $request->category));
            session()->flash('success_message', $component->name . ' has been updated successfully');

            return to_route("components.index");
        }
    }

    public function destroy(Component $component)
    {
        if(auth()->user()->cant('delete', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Delete Components.');

        } else
        {
            $name = $component->name;
            $component->delete();
            session()->flash('danger_message', $name . ' was deleted from the system');

            return to_route('components.index');
        }

    }

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Export Components.');

        }
        $components = Component::all();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new ComponentsExport($components), "/public/csv/components-ex-{$date}.xlsx");
        $url = asset("storage/csv/components-ex-{$date}.xlsx");

        return to_route('components.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Import Components.');

        }

        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
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

                return view('ComponentsDir.import-errors', [
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

                return to_route('components.index')->with('success_message', 'All Components were added correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('components.index');
        }
    }

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Download Components.');

        }

        $components = array();
        $found = Component::withTrashed()->whereIn('id', json_decode($request->components))->get();
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
            $components[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'components-' . $date;

        dispatch(new ComponentsPdf($components, $user, $path))->afterResponse();
        //Create Report

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('components.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Component $component)
    {
        if(auth()->user()->cant('view', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Download Components.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'component-' . $component->id . '-' . $date;

        dispatch(new ComponentPdf($component, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('components.show', $component->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    //Restore and Force Delete Function Need to be Created

    public function recycleBin()
    {
        if(auth()->user()->cant('viewAll', Component::class))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Recycle Components.');

        }

        $components = auth()->user()->location_components()->onlyTrashed()->get();
        $locations = auth()->user()->locations;

        return view('ComponentsDir.bin', ["components" => $components,]);
    }

    public function restore($id)
    {
        $component = Component::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Restore Components.');

        }
        $name = $component->name;
        $component->restore();
        session()->flash('success_message', "#" . $name . ' has been restored.');

        return to_route("components.index");
    }

    public function forceDelete($id)
    {
        $component = Component::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $component))
        {
            return ErrorController::forbidden(to_route('components.index'), 'Unauthorised to Delete Components.');

        }
        $name = $component->name;
        $component->forceDelete();
        session()->flash('danger_message', "Component - " . $name . ' was deleted permanently');

        return to_route("components.bin");
    }

}
