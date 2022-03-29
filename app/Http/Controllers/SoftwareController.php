<?php

namespace App\Http\Controllers;

use App\Exports\SoftwareErrorsExport;
use App\Exports\SoftwareExport;
use App\Imports\SoftwareImport;
use App\Jobs\PropertiesPdf;
use App\Jobs\softwarePdf;
use App\Jobs\SoftwaresPdf;
use App\Models\AUC;
use App\Models\Location;
use App\Models\software;
use App\Models\Report;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use PDF;

class SoftwareController extends Controller {

    public function index()
    {
        //Check to see if the User has permission to View All the Software.

        if(auth()->user()->cant('viewAll', Software::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised | View Software.');

        }
        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('software')->get();
        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $softwares = Software::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        return view('software.view', [
            "softwares" => $softwares,
            "locations" => $locations,
        ]);
    }

    public function create()
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('create', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Create Software.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('software.create', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        //Check to see if the user has permission to add new software on the system

        if(auth()->user()->cant('create', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Store Software.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'purchased_date' => 'required|date',
        ]);

        Software::create([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'depreciation' => $request->depreciation,
        ]);

        return to_route('softwares.index')->with('success_message', $request->name . ' Has been Added!');
    }

    public function show(Software $software)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('view', $software))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to update Software.');

        }

        // Return the Create View to the browser
        return view('software.show', [
            "software" => $software,
        ]);
    }

    public function edit(Software $software)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('update', $software))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to update Software.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('software.edit', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
            "software" => $software,
        ]);
    }

    public function update(Request $request, Software $software)
    {
        //Check to see if the user has permission to update software on the system
        if(auth()->user()->cant('update', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Update Software.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'purchased_date' => 'required|date',
        ]);

        $software->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'depreciation' => $request->depreciation,
        ]);

        return to_route('softwares.index')->with('success_message', $request->name . ' Has been Updated!');
    }

    public function recycleBin()
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('delete', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Delete Software.');

        }
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');

        // Return the Create View to the browser
        return view('software.bin', [
            "locations" => $locations,
            "softwares" => Software::onlyTrashed()->paginate(),
        ]);

    }

    public function destroy(Software $software)
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('recycleBin', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Archive Software.');

        }
        $software->delete();

        return to_route('softwares.index')->with('success_message', $software->name . ' Has been sent to the recycle bin!');

    }

    public function restore($id)
    {
        //Find the software (withTrashed needed)
        $software = Software::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the software
        if(auth()->user()->cant('delete', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Restore Software.');

        }

        //Restores the software
        $software->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $software->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('softwares.index');
    }

    public function forceDelete($id)
    {
        //Find the software (withTrashed needed)
        $software = Software::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the software
        if(auth()->user()->cant('delete', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Delete Software.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $software->name;
        //Force Delete removes the model permanently from the system
        $software->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('software.bin');
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

        $software = Software::find($request->software_id);
        $software->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('softwares.show', $software->id);
    }

    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised | Download of Software Information Report.');

        }
        $softwares = array();
        $found = Software::select('name', 'id', 'depreciation', 'supplier_id', 'purchased_date', 'purchased_cost', 'location_id', 'created_at')->withTrashed()->whereIn('id', json_decode($request->software))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['depreciation'] = $f->depreciation;
            $array['supplier'] = $f->supplier->name ?? 'No Supplier';
            $softwares[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'properties-report-' . $date;
        SoftwaresPdf::dispatch($softwares, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('softwares.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Software $software)
    {
        if(auth()->user()->cant('view', $software))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised | Download of Software Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "software-{$software->id}-{$date}";
        SoftwarePdf::dispatch($software, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('softwares.show', $software->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised | Import Software.');

        }
        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(
            //checks for spelling and if there present for any allowed heading in the csv.
            isset($values['name']) && isset($values['supplier_id']) && isset($values['location_id'])
            && isset($values['depreciation']) && isset($values['purchased_date']) && isset($values['purchased_cost'])
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
            $import = new SoftwareImport;
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

                return view('software.importErrors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                    "suppliers" => Supplier::all(),
                ]);

            } else
            {
                return to_route('softwares.index')->with('success_message', 'All Softwares were imported correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('softwares.index');
        }


    }

    public function importErrors(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name.*" => "required|max:255",
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
                $software = new Software;
                $software->name = $request->name[$i];
                $software->supplier_id = $request->supplier_id[$i];
                $software->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $software->purchased_cost = $request->purchased_cost[$i];
                $software->location_id = $request->location_id[$i];
                $software->depreciation = $request->depreciation[$i];
                $software->save();
            }

            session()->flash('success_message', 'You have successfully added all Software Items!');

            return 'Success';
        }
    }

    ////////////////////////////////////////////////////////
    ///////////////Export Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised | Export Software Information.');

        }
        $softwares = Software::withTrashed()->whereIn('id', json_decode($request->software))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new softwareExport($softwares), "/public/csv/softwares-{$date}.xlsx");
        $url = asset("storage/csv/softwares-{$date}.xlsx");

        return to_route('softwares.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        if(auth()->user()->cant('viewAll', Software::class))
        {
            return ErrorController::forbidden(to_route('softwares.index'), 'Unauthorised to Export Software Errors.');
        }
        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new softwareErrorsExport($export), "/public/csv/software-errors-{$date}.csv");
        $url = asset("storage/csv/software-errors-{$date}.csv");

        return to_route('softwares.index')
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
                session(['software_search' => $request->search]);
            }

            if(! empty($request->limit))
            {
                session(['software_limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);

                session(['software_orderby' => $array[0]]);
                session(['software_direction' => $array[1]]);

            }

            if(! empty($request->locations))
            {
                session(['software_locations' => $request->locations]);
            }

            if($request->start != '' && $request->end != '')
            {
                session(['software_start' => $request->start]);
                session(['software_end' => $request->end]);
            }

            session(['assets_min' => $request->minCost]);
            session(['assets_max' => $request->maxCost]);
        }
        //Check the Users Locations Permissions
        $locations = Location::select('id', 'name')->withCount('software')->get();

        $software = software::locationFilter($locations->pluck('id'));

        if(session()->has('software_locations'))
        {
            $software->locationFilter(session('software_locations'));
            session(['software_filter' => true]);
        }

        if(session()->has('software_start') && session()->has('software_end'))
        {
            $software->purchaseFilter(session('software_start'), session('software_end'));
            session(['software_filter' => true]);
        }

        if(session()->has('assets_min') && session()->has('assets_max'))
        {
            $software->costFilter(session('assets_min'), session('assets_max'));
            session(['assets_filter' => true]);
        }

        if(session()->has('software_search'))
        {
            $software->searchFilter(session('software_search'));
            session(['software_filter' => true]);
        }

        $software->leftJoin('locations', 'software.location_id', '=', 'locations.id')
            ->orderBy(session('software_orderby') ?? 'date', session('software_direction') ?? 'asc')
            ->select('software.*', 'locations.name as location_name');
        $limit = session('software_limit') ?? 25;

        return view('software.view', [
            "softwares" => $software->paginate(intval($limit))->withPath(asset('/software/filter'))->fragment('table'),
            "locations" => $locations,
        ]);
    }

    public function clearFilter()
    {
        //Clear the Filters for the properties
        session()->forget(['software_filter', 'software_locations', 'software_start', 'software_end', 'software_amount', 'software_search']);

        return to_route('softwares.index');
    }

}
