<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;

//Exports
use App\Exports\PropertyExport;
use App\Exports\PropertyErrorsExport;

//Imports
use App\Imports\PropertyImport;

//Models
use App\Models\Report;

//Jobs
use App\Jobs\PropertiesPdf;
use App\Jobs\PropertyPdf;

use Illuminate\Support\Facades\Validator;

use App\Rules\permittedLocation;
use App\Rules\findLocation;

use \Carbon\Carbon;

class PropertyController extends Controller {

    ////////////////////////////////////////////
    ////////////// View Functions //////////////
    ////////////////////////////////////////////

    public function index()
    {
        //Check to see if the User has permission to View All the Properties.
        if(auth()->user()->cant('viewAll', Property::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Properties.');

        }

        //If there are filters currently set move to filtered function
        if(session()->has('property_filter') && session('property_filter') === true)
        {
            return to_route('property.filtered');
        }

        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('property')->get();

        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $properties = Property::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        return view('property.view', [
            "properties" => $properties,
            "locations" => $locations,
        ]);
    }

    public function show(Property $property)
    {
        if(auth()->user()->cant('view', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Show Properties.');

        }

        //This function returns the property and displays the information about it on the View
        return view('property.show', compact('property'));
    }

    ////////////////////////////////////////////
    ////////////// Create Functions ////////////
    ////////////////////////////////////////////

    public function create()
    {
        if(auth()->user()->cant('create', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Create Properties.');

        }

        $locations = auth()->user()->locations;

        return view('property.create', [
            "locations" => $locations,
        ]);
    }

    public function store(Request $request)
    {
        //Store the new property in the database

        //Check to see if the user has permission to add nw property on the system
        if(auth()->user()->cant('create', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Store Properties.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0',
        ]);

        $property = new Property;

        $property->fill([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'donated' => $request->donated,
            'depreciation' => $request->depreciation,
            'type' => $request->type,
            'purchased_date' => $request->purchased_date,
            'user_id' => auth()->user()->id,
        ])->save();

        session()->flash('success_message', $request->name . ' has been created successfully');

        return to_route('properties.index');
    }


    ////////////////////////////////////////////
    ////////////// Update Functions ////////////
    ////////////////////////////////////////////

    public function edit(Property $property)
    {
        // Check to see whether the user has permission to edit the sleected property
        if(auth()->user()->cant('update', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Edit Properties.');

        }

        return view('property.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        // Check to see whether the user has permission to edit the selected property
        if(auth()->user()->cant('update', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Update Properties.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0',
        ]);

        //Fill the Model fields from the request
        $property->fill($request->only('name', 'location_id', 'purchased_cost', 'donated', 'purchased_date', 'depreciation', 'type'))->save();

        //Return the session message to the index
        session()->flash('success_message', $request->name . ' has been updated successfully');

        //return to the view
        return to_route('properties.index');

    }

    ////////////////////////////////////////////
    ////////////// Delete Functions ////////////
    ////////////////////////////////////////////

    public function destroy(Property $property)
    {
        //Check to see whether the User has permissions to remove the property or send it to the Recycle Bin
        if(auth()->user()->cant('delete', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Delete Properties.');

        }

        $name = $property->name;

        $property->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return to_route('properties.index');
    }

    public function recycleBin()
    {
        if(auth()->user()->cant('recycleBin', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Recycle Properties.');

        }

        $limit = session('property_limit') ?? 25;

        //Check the User Location Permissions
        $properties = auth()->user()->location_property()->onlyTrashed()->paginate(intval($limit))->fragment('table');
        $locations = auth()->user()->locations;

        return view('property.bin', [
            "properties" => $properties,
            "locations" => $locations,
        ]);
    }

    public function restore($id)
    {
        //Find the Property (withTrashed needed)
        $property = Property::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the property
        if(auth()->user()->cant('delete', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Restore Properties.');

        }

        //Restores the Property
        $property->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $property->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('properties.index');
    }

    public function forceDelete($id)
    {
        //Find the Property (withTrashed needed)
        $property = Property::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the property
        if(auth()->user()->cant('delete', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Delete Properties.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $property->name;
        //Force Delete removes the model permanently from the system
        $property->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('property.bin');
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

        $property = Property::find($request->property_id);
        $property->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('properties.show', $property->id);
    }

    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised | Download of Property Information Report.');

        }
        $properties = array();
        $found = Property::select('name', 'id', 'depreciation', 'type', 'purchased_date', 'purchased_cost', 'location_id')->withTrashed()->whereIn('id', json_decode($request->property))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['depreciation'] = $f->depreciation;
            $array['current_value'] = $f->depreciation_value(\Carbon\Carbon::now());
            $array['type'] = $f->getType();
            $properties[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'properties-report-' . $date;

        PropertiesPdf::dispatch($properties, $user, $path)->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('properties.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Property $property)
    {
        if(auth()->user()->cant('view', $property))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised | Download of Property Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "property-{$property->id}-{$date}";
        PropertyPdf::dispatch($property, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('properties.show', $property->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised | Import Properties.');

        }
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new PropertyImport;
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

                return view('property.importErrors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                ]);

            } else
            {
                return to_route('properties.index')->with('success_message', 'All Properties were imported correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('properties.index');
        }


    }

    public function importErrors(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name.*" => "required|max:255",
            'location_id.*' => 'required|gt:0',
            'purchased_date.*' => 'date',
            'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            "depreciation.*" => "nullable",
            "type.*" => "nullable",
        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                $property = new Property;
                $property->name = $request->name[$i];
                $property->type = $request->type[$i];
                $property->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $property->purchased_cost = $request->purchased_cost[$i];
                $property->location_id = $request->location_id[$i];
                $property->depreciation = $request->depreciation[$i];
                $property->save();
            }

            session()->flash('success_message', 'You have successfully added all Properties!');

            return 'Success';
        }
    }

    ////////////////////////////////////////////////////////
    ///////////////Export Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised | Export Property Information.');

        }
        $properties = Property::withTrashed()->whereIn('id', json_decode($request->properties))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new PropertyExport($properties), "/public/csv/properties-{$date}.xlsx");
        $url = asset("storage/csv/properties-{$date}.xlsx");

        return to_route('properties.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if(auth()->user()->cant('viewAll', Property::class))
        {
            return ErrorController::forbidden(route('properties.index'), 'Unauthorised to Export Property Errors.');

        }

        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new PropertyErrorsExport($export), "/public/csv/property-errors-{$date}.csv");
        $url = asset("storage/csv/property-errors-{$date}.csv");

        return to_route('properties.index')
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
                session(['property_search' => $request->search]);
            }

            if(! empty($request->limit))
            {
                session(['property_limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);

                session(['property_orderby' => $array[0]]);
                session(['property_direction' => $array[1]]);

            }

            if(! empty($request->locations))
            {
                session(['property_locations' => $request->locations]);
            }

            if($request->start != '' && $request->end != '')
            {
                session(['property_start' => $request->start]);
                session(['property_end' => $request->end]);
            }

            session(['property_min' => $request->minCost]);
            session(['property_max' => $request->maxCost]);
        }
        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('property')->get();

        $property = Property::locationFilter($locations->pluck('id'));

        if(session()->has('property_locations'))
        {
            $property->locationFilter(session('property_locations'));
            session(['property_filter' => true]);
        }

        if(session()->has('property_start') && session()->has('property_end'))
        {
            $property->purchaseFilter(Carbon::parse(session('property_start')), Carbon::parse(session('property_end')));
            session(['property_filter' => true]);
        }

        if(session()->has('property_min') && session()->has('property_max'))
        {
            $property->costFilter(session('property_min'), session('property_max'));
            session(['property_filter' => true]);
        }

        if(session()->has('property_search'))
        {
            $property->searchFilter(session('property_search'));
            session(['property_filter' => true]);
        }

        $property->leftJoin('locations', 'properties.location_id', '=', 'locations.id')
            ->orderBy(session('property_orderby') ?? 'purchased_date', session('property_direction') ?? 'asc')
            ->select('properties.*', 'locations.name as location_name');
        $limit = session('property_limit') ?? 25;

        return view('property.view', [
            "properties" => $property->paginate(intval($limit))->withPath(asset('/property/filter'))->fragment('table'),
            "locations" => $locations,
        ]);
    }

    public function clearFilter()
    {
        //Clear the Filters for the properties
        session()->forget(['property_filter', 'property_locations', 'property_start', 'property_end', 'property_min', 'property_max', 'property_search']);

        return to_route('properties.index');
    }

}
