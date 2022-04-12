<?php

namespace App\Http\Controllers;

use App\Exports\machineryErrorsExport;
use App\Exports\machineryExport;
use App\Imports\MachineryImport;
use App\Jobs\machineryPdf;
use App\Jobs\machineriesPdf;
use App\Models\Location;
use App\Models\Machinery;
use App\Models\Report;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;

class MachineryController extends Controller {

    public function index()
    {
        //Check to see if the User has permission to View All the machinery.

        if(auth()->user()->cant('viewAll', Machinery::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised | View machineries.');
        }

        //If there are filters currently set move to filtered function
        if(session()->has('machinery_filter') && session('machinery_filter') === true)
        {
            return to_route('machinery.filtered');
        }

        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('machinery')->get();
        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $machineries = Machinery::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        return view('machinery.view', [
            "machineries" => $machineries,
            "locations" => $locations,
        ]);
    }

    public function create()
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('create', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Create machinery.');
        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('machinery.create', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        //Check to see if the user has permission to add new machinery on the system

        if(auth()->user()->cant('create', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Store machinery.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'purchased_date' => 'required|date',
        ]);
        Machinery::create([
            'name' => $request->name,
            'description' => $request->description,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'depreciation' => $request->depreciation,
        ]);

        return to_route('machineries.index')->with('success_message', $request->name . ' Has been Added!');
    }

    public function show(Machinery $machinery)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('view', $machinery))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to update machinery.');

        }

        // Return the Create View to the browser
        return view('machinery.show', [
            "machinery" => $machinery,
        ]);
    }

    public function edit(machinery $machinery)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('update', $machinery))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to update machinery.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('machinery.edit', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
            "machinery" => $machinery,
        ]);
    }

    public function update(Request $request, Machinery $machinery)
    {
        //Check to see if the user has permission to update machinery on the system
        if(auth()->user()->cant('update', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Update machinery.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'purchased_date' => 'required|date',
        ]);

        $machinery->update([
            'name' => $request->name,
            'description' => $request->description,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'depreciation' => $request->depreciation,
        ]);

        return to_route('machineries.index')->with('success_message', $request->name . ' Has been Updated!');
    }

    public function recycleBin()
    {
        //Check to see if the user has permission to delete machinery on the system
        if(auth()->user()->cant('delete', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Delete machinery.');

        }
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');

        // Return the Create View to the browser
        return view('machinery.bin', [
            "locations" => $locations,
            "machineries" => Machinery::onlyTrashed()->paginate(),
        ]);

    }

    public function destroy(Machinery $machinery)
    {
        //Check to see if the user has permission to delete machinery on the system
        if(auth()->user()->cant('recycleBin', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Archive machinery.');

        }
        $machinery->delete();

        return to_route('machineries.index')->with('success_message', $machinery->name . ' Has been sent to the recycle bin!');

    }

    public function restore($id)
    {
        //Find the machinery (withTrashed needed)
        $machinery = Machinery::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the machinery
        if(auth()->user()->cant('delete', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Restore machinery.');

        }

        //Restores the machinery
        $machinery->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $machinery->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('machineries.index');
    }

    public function forceDelete($id)
    {
        //Find the machinery (withTrashed needed)
        $machinery = Machinery::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the machinery
        if(auth()->user()->cant('delete', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Delete machinery.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $machinery->name;
        //Force Delete removes the model permanently from the system
        $machinery->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('machinery.bin');
    }

    ////////////////////////////////////////
    /////////// Comment Functions ///////////
    ////////////////////////////////////////

    public function newComment(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $machinery = Machinery::find($request->machinery_id);
        $machinery->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('machineries.show', $machinery->id);
    }

    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised | Download of machinery Information Report.');

        }
        $machineries = array();
        $found = Machinery::select('name', 'id', 'description', 'depreciation', 'supplier_id', 'purchased_date', 'purchased_cost', 'location_id', 'created_at')->withTrashed()->whereIn('id', json_decode($request->machinery))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['description'] = $f->description ?? 'No description';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['depreciation'] = $f->depreciation;
            $array['supplier'] = $f->supplier->name ?? 'No Supplier';
            $machineries[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'machinery-report-' . $date;
        machineriesPdf::dispatch($machineries, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('machineries.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Machinery $machinery)
    {
        if(auth()->user()->cant('view', $machinery))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised | Download of machinery Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "machinery-{$machinery->id}-{$date}";
        MachineryPdf::dispatch($machinery, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('machineries.show', $machinery->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised | Import machinery.');

        }
        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(
            //checks for spelling and if there present for any allowed heading in the csv.
            isset($values['name']) && isset($values['supplier_id']) && isset($values['location_id'])
            && isset($values['depreciation']) && isset($values['purchased_date'])
            && isset($values['purchased_cost']) && isset($values['description'])
        )
        {
        } else
        {
            return to_route('assets.index')->with('danger_message', "CSV Heading's Incorrect Please amend and try again!");
        }
        //headings incorrect end
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new MachineryImport;
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

                return view('machinery.importErrors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                    "suppliers" => Supplier::all(),
                ]);

            } else
            {
                return to_route('machineries.index')->with('success_message', 'All machineries were imported correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('machineries.index');
        }


    }

    public function importErrors(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name.*" => "required|max:255",
            "description.*" => "required|max:255",
            'location_id.*' => 'required|gt:0',
            'supplier_id.*' => 'required|gt:0',
            'purchased_date.*' => 'date',
            'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            "depreciation.*" => "nullable",
        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                $machinery = new machinery;
                $machinery->name = $request->name[$i];
                $machinery->description = $request->description[$i];
                $machinery->supplier_id = $request->supplier_id[$i];
                $machinery->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $machinery->purchased_cost = $request->purchased_cost[$i];
                $machinery->location_id = $request->location_id[$i];
                $machinery->depreciation = $request->depreciation[$i];
                $machinery->save();
            }

            session()->flash('success_message', 'You have successfully added all machinery Items!');

            return 'Success';
        }
    }

    ////////////////////////////////////////////////////////
    ///////////////Export Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised | Export machinery Information.');

        }
        $machineries = Machinery::withTrashed()->whereIn('id', json_decode($request->machinery))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new MachineryExport($machineries), "/public/csv/machineries-{$date}.xlsx");
        $url = asset("storage/csv/machineries-{$date}.xlsx");

        return to_route('machineries.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        if(auth()->user()->cant('viewAll', Machinery::class))
        {
            return ErrorController::forbidden(route('machineries.index'), 'Unauthorised to Export machinery Errors.');
        }
        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new MachineryErrorsExport($export), "/public/csv/machinery-errors-{$date}.csv");
        $url = asset("storage/csv/machinery-errors-{$date}.csv");

        return to_route('machineries.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    ////////////////////////////////////////
    /////////// Filter Functions ///////////
    ////////////////////////////////////////

    public function filter(Request $request)
    {
        //Check to see if the Request is the POST or GET Method
        if($request->isMethod('post'))
        {
            //If the request is a POST method then the filter needs to be set or redefined

            //Check to see if the filter fields are empty
            if(! empty($request->search))
            {
                //If they are not empty assign the filter type to the session
                session(['machinery_search' => $request->search]);
            }

            if(! empty($request->limit))
            {
                session(['machinery_limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);

                session(['machinery_orderby' => $array[0]]);
                session(['machinery_direction' => $array[1]]);

            }

            if(! empty($request->locations))
            {
                session(['machinery_locations' => $request->locations]);
            }

            if($request->start != '' && $request->end != '')
            {
                session(['machinery_start' => $request->start]);
                session(['machinery_end' => $request->end]);
            }

            session(['machinery_min' => $request->minCost]);
            session(['machinery_max' => $request->maxCost]);
        }
        //Check the Users Locations Permissions
        $locations = Location::select('id', 'name')->withCount('machinery')->get();

        $machinery = Machinery::locationFilter($locations->pluck('id'));

        if(session()->has('machinery_locations'))
        {
            $machinery->locationFilter(session('machinery_locations'));
            session(['machinery_filter' => true]);
        }

        if(session()->has('machinery_start') && session()->has('machinery_end'))
        {
            $machinery->purchaseFilter(session('machinery_start'), session('machinery_end'));
            session(['machinery_filter' => true]);
        }

        if(session()->has('machinery_min') && session()->has('assets_max'))
        {
            $machinery->costFilter(session('assets_min'), session('machinery_max'));
            session(['machinery_filter' => true]);
        }

        if(session()->has('machinery_search'))
        {
            $machinery->searchFilter(session('machinery_search'));
            session(['machinery_filter' => true]);
        }

        $machinery->leftJoin('locations', 'machineries.location_id', '=', 'locations.id')
            ->orderBy(session('machinery_orderby') ?? 'purchased_date', session('machinery_direction') ?? 'asc')
            ->select('machineries.*', 'locations.name as location_name');

        $limit = session('machinery_limit') ?? 25;

        return view('machinery.view', [
            "machineries" => $machinery->paginate(intval($limit))->withPath(asset('/machinery/filter'))->fragment('table'),
            "locations" => $locations,
        ]);
    }

    public function clearFilter()
    {
        //Clear the Filters for the properties
        session()->forget(['machinery_filter', 'machinery_locations', 'machinery_start', 'machinery_end', 'machinery_min', 'machinery_max', 'machinery_search']);

        return to_route('machineries.index');
    }

}
