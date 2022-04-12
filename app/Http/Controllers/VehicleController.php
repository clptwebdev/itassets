<?php

namespace App\Http\Controllers;

use App\Exports\vehicleErrorsExport;
use App\Exports\vehicleExport;
use App\Imports\vehicleImport;
use App\Jobs\vehiclePdf;
use App\Jobs\vehiclesPdf;
use App\Models\Location;
use App\Models\Report;
use App\Models\Vehicle;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;

class VehicleController extends Controller {

    ///////////////////////////////////////
    //////// View Fucntions ///////////////
    ///////////////////////////////////////

    public function index()
    {
        //Check to see if the User has permission to View All the vehicle.

        if(auth()->user()->cant('viewAll', Vehicle::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised | View Vehicles.');
        }
        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('vehicle')->get();
        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $vehicles = Vehicle::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        return view('vehicle.view', [
            "vehicles" => $vehicles,
            "locations" => $locations,
        ]);
    }

    public function show(Vehicle $vehicle)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('view', $vehicle))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to update vehicle.');

        }

        // Return the Create View to the browser
        return view('vehicle.show', [
            "vehicle" => $vehicle,
        ]);
    }

    public function recycleBin()
    {
        //Check to see if the user has permission to delete vehicle on the system
        if(auth()->user()->cant('delete', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Delete vehicle.');

        }
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');

        // Return the Create View to the browser
        return view('vehicle.bin', [
            "locations" => $locations,
            "vehicles" => Vehicle::onlyTrashed()->paginate(),
        ]);

    }

    ///////////////////////////////////////
    //////// Create Fucntions /////////////
    ///////////////////////////////////////

    public function create()
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('create', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Create vehicle.');
        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('vehicle.create', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        //Check to see if the user has permission to add new vehicle on the system

        if(auth()->user()->cant('create', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Store vehicle.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'registration' => 'required',
            'location_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'purchased_date' => 'required|date',
        ], [
            'name.required' => 'You must provide a name to reference the Vehicle!',
            'registration.required' => 'Please enter the Vehicle Registration',
            'location_id.required' => 'Please assign the Vehicle to a Location',
            'purchased_cost.required' => 'The purchased cost for the Vehicle is empty!',
            'purchased_cost.regex' => 'The purchased cost is not in a valid format. Please enter a decmial currency without the £ symbol',
            'depreciation.required' => 'Please enter a depreciation value, this is a number of years',
            'depreciation.numeric' => 'The depreciation for the Vehicle is a number of years - the value is currently invalid',
            'purchased_date.required' => 'Please enter the date the Vehicle was purchased',
            'purchased_date.date' => 'An invalid date was entered for the Purchased Date, please follow the format: dd/mm/YYYY',

        ]);
        Vehicle::create([
            'name' => $request->name,
            'registration' => $request->registration,
            'location_id' => $request->location_id,
            'supplier_id' => $request->supplier_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'depreciation' => $request->depreciation,
        ]);

        return to_route('vehicles.index')->with('success_message', $request->name . ' Has been Added!');
    }

    ///////////////////////////////////////
    //////// Edit Fucntions ///////////////
    ///////////////////////////////////////

    public function edit(vehicle $vehicle)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('update', $vehicle))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to update vehicle.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('vehicle.edit', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
            "vehicle" => $vehicle,
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        //Check to see if the user has permission to update vehicle on the system
        if(auth()->user()->cant('update', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Update vehicle.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'registration' => 'required',
            'location_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'purchased_date' => 'required|date',
        ], [
            'name.required' => 'You must provide a name to reference the Vehicle!',
            'registration.required' => 'Please enter the Vehicle Registration',
            'location_id.required' => 'Please assign the Vehicle to a Location',
            'purchased_cost.required' => 'The purchased cost for the Vehicle is empty!',
            'purchased_cost.regex' => 'The purchased cost is not in a valid format. Please enter a decmial currency without the £ symbol',
            'depreciation.required' => 'Please enter a depreciation value, this is a number of years',
            'depreciation.numeric' => 'The depreciation for the Vehicle is a number of years - the value is currently invalid',
            'purchased_date.required' => 'Please enter the date the Vehicle was purchased',
            'purchased_date.date' => 'An invalid date was entered for the Purchased Date, please follow the format: dd/mm/YYYY',

        ]);

        $vehicle->update([
            'name' => $request->name,
            'registration' => $request->registration,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'depreciation' => $request->depreciation,
        ]);

        return to_route('vehicles.index')->with('success_message', $request->name . ' Has been Updated!');
    }

    ///////////////////////////////////////
    //////// Delete Fucntions /////////////
    ///////////////////////////////////////

    public function destroy(Vehicle $vehicle)
    {
        //Check to see if the user has permission to delete vehicle on the system
        if(auth()->user()->cant('recycleBin', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Archive vehicle.');

        }
        $vehicle->delete();

        return to_route('vehicles.index')->with('success_message', $vehicle->name . ' Has been sent to the recycle bin!');

    }

    public function restore($id)
    {
        //Find the vehicle (withTrashed needed)
        $vehicle = Vehicle::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the vehicle
        if(auth()->user()->cant('delete', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Restore vehicle.');

        }

        //Restores the vehicle
        $vehicle->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $vehicle->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('vehicles.index');
    }

    public function forceDelete($id)
    {
        //Find the vehicle (withTrashed needed)
        $vehicle = Vehicle::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the vehicle
        if(auth()->user()->cant('delete', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Delete vehicle.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $vehicle->name;
        //Force Delete removes the model permanently from the system
        $vehicle->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('vehicle.bin');
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

        $vehicle = Vehicle::find($request->vehicle_id);
        $vehicle->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('vehicles.show', $vehicle->id);
    }

    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised | Download of vehicle Information Report.');

        }
        $vehicles = array();
        $found = Vehicle::select('name', 'id', 'registration', 'depreciation', 'supplier_id', 'purchased_date', 'purchased_cost', 'location_id', 'created_at')->withTrashed()->whereIn('id', json_decode($request->vehicle))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['registration'] = $f->registration ?? 'No registration';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['current_value'] = $f->depreciation_value();
            $array['depreciation'] = $f->depreciation;
            $array['supplier'] = $f->supplier->name ?? 'No Supplier';
            $vehicles[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'vehicle-report-' . $date;
        vehiclesPdf::dispatch($vehicles, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('vehicles.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Vehicle $vehicle)
    {
        if(auth()->user()->cant('view', $vehicle))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised | Download of vehicle Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "vehicle-{$vehicle->id}-{$date}";
        vehiclePdf::dispatch($vehicle, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('vehicles.show', $vehicle->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised | Import vehicle.');

        }
        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(
            //checks for spelling and if there present for any allowed heading in the csv.
            isset($values['name']) && isset($values['supplier_id']) && isset($values['location_id'])
            && isset($values['depreciation']) && isset($values['purchased_date'])
            && isset($values['purchased_cost']) && isset($values['registration'])
        )
        {
        } else
        {
            return to_route('vehicles.index')->with('danger_message', "CSV Heading's Incorrect Please amend and try again!");
        }
        //headings incorrect end
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new VehicleImport;
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

                return view('vehicle.importErrors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                    "suppliers" => Supplier::all(),
                ]);

            } else
            {
                return to_route('vehicles.index')->with('success_message', 'All vehicles were imported correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('vehicles.index');
        }


    }

    public function importErrors(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name.*" => "required|max:255",
            "registration.*" => "required|max:255",
            'location_id.*' => 'required|gt:0',
            'supplier_id.*' => 'required|gt:0',
            'purchased_date.*' => 'date',
            'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            "depreciation.*" => "nullable",
        ], [
            'name.*.required' => 'You must provide a name to reference the Vehicle!',
            'registration.*.required' => 'Please enter the Vehicle Registration',
            'location_id.*.required' => 'Please assign the Vehicle to a Location',
            'purchased_cost.*.required' => 'The purchased cost for the Vehicle is empty!',
            'purchased_cost.*.regex' => 'The purchased cost is not in a valid format. Please enter a decmial currency without the £ symbol',
            'depreciation.*.required' => 'Please enter a depreciation value, this is a number of years',
            'depreciation.*.numeric' => 'The depreciation for the Vehicle is a number of years - the value is currently invalid',
            'purchased_date.*.required' => 'Please enter the date the Vehicle was purchased',
            'purchased_date.*.date' => 'An invalid date was entered for the Purchased Date, please follow the format: dd/mm/YYYY',

        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                $vehicle = new vehicle;
                $vehicle->name = $request->name[$i];
                $vehicle->registration = $request->registration[$i];
                $vehicle->supplier_id = $request->supplier_id[$i];
                $vehicle->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $vehicle->purchased_cost = $request->purchased_cost[$i];
                $vehicle->location_id = $request->location_id[$i];
                $vehicle->depreciation = $request->depreciation[$i];
                $vehicle->save();
            }

            session()->flash('success_message', 'You have successfully added all vehicle Items!');

            return 'Success';
        }
    }

    ////////////////////////////////////////////////////////
    ///////////////Export Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised | Export vehicle Information.');

        }
        $vehicles = Vehicle::withTrashed()->whereIn('id', json_decode($request->vehicle))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new VehicleExport($vehicles), "/public/csv/vehicles-{$date}.xlsx");
        $url = asset("storage/csv/vehicles-{$date}.xlsx");

        return to_route('vehicles.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        if(auth()->user()->cant('viewAll', Vehicle::class))
        {
            return ErrorController::forbidden(route('vehicles.index'), 'Unauthorised to Export vehicle Errors.');
        }
        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new VehicleErrorsExport($export), "/public/csv/vehicle-errors-{$date}.csv");
        $url = asset("storage/csv/vehicle-errors-{$date}.csv");

        return to_route('vehicles.index')
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
                session(['vehicle_search' => $request->search]);
            }

            if(! empty($request->limit))
            {
                session(['vehicle_limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);

                session(['vehicle_orderby' => $array[0]]);
                session(['vehicle_direction' => $array[1]]);

            }

            if(! empty($request->locations))
            {
                session(['vehicle_locations' => $request->locations]);
            }

            if($request->start != '' && $request->end != '')
            {
                session(['vehicle_start' => $request->start]);
                session(['vehicle_end' => $request->end]);
            }

            session(['vehicle_min' => $request->minCost]);
            session(['vehicle_max' => $request->maxCost]);
        }
        //Check the Users Locations Permissions
        $locations = Location::select('id', 'name')->withCount('vehicle')->get();

        $vehicle = Vehicle::locationFilter($locations->pluck('id'));

        if(session()->has('vehicle_locations'))
        {
            $vehicle->locationFilter(session('vehicle_locations'));
            session(['vehicle_filter' => true]);
        }

        if(session()->has('vehicle_start') && session()->has('vehicle_end'))
        {
            $vehicle->purchaseFilter(session('vehicle_start'), session('vehicle_end'));
            session(['vehicle_filter' => true]);
        }

        if(session()->has('vehicle_min') && session()->has('vehicle_max'))
        {
            $vehicle->costFilter(session('vehicle_min'), session('vehicle_max'));
            session(['vehicle_filter' => true]);
        }

        if(session()->has('vehicle_search'))
        {
            $vehicle->searchFilter(session('vehicle_search'));
            session(['vehicle_filter' => true]);
        }

        $vehicle->leftJoin('locations', 'vehicles.location_id', '=', 'locations.id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'vehicles.supplier_id')
            ->select('vehicles.*', 'locations.name as location_name', 'suppliers.name as supplier_name')
            ->orderBy(session('vehicle_orderby') ?? 'purchased_date', session('vehicle_direction') ?? 'asc');
        $limit = session('vehicle_limit') ?? 25;

        return view('vehicle.view', [
            "vehicles" => $vehicle->paginate(intval($limit))->withPath(asset('/vehicle/filter'))->fragment('table'),
            "locations" => $locations,
        ]);
    }

    public function clearFilter()
    {
        //Clear the Filters for the properties
        session()->forget(['vehicle_filter', 'vehicle_locations', 'vehicle_start', 'vehicle_end', 'vehicle_min', 'vehicle_max', 'vehicle_search']);

        return to_route('vehicles.index');
    }

}
