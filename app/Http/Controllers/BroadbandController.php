<?php

namespace App\Http\Controllers;

use App\Exports\BroadbandErrorsExport;
use App\Exports\BroadbandExport;
use App\Exports\SoftwareErrorsExport;
use App\Exports\SoftwareExport;
use App\Imports\BroadbandImport;
use App\Imports\SoftwareImport;
use App\Jobs\BroadbandPdf;
use App\Jobs\BroadbandsPdf;
use App\Jobs\SoftwarePdf;
use App\Jobs\SoftwaresPdf;
use App\Models\Broadband;
use App\Models\Location;
use App\Models\Report;
use App\Models\Software;
use App\Models\Supplier;
use http\Encoding\Stream\Debrotli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;

class BroadbandController extends Controller {

    public function index()
    {
        //Check to see if the User has permission to View All the Software.
        if(auth()->user()->cant('viewAll', Broadband::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised to View Broadband.');

        }

        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');
        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $broadbands = Broadband::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        return view('broadband.view', [
            "broadbands" => $broadbands,
            "locations" => $locations,
        ]);
    }

    public function create()
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('create', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Create Broadband.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('broadband.create', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        //Check to see if the user has permission to add new software on the system

        if(auth()->user()->cant('create', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Store Broadband.');

        }

        //Validate the post data
        $validation = $request->validate([
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'purchased_date' => 'required|date',
            'renewal_date' => 'required|date',
        ]);

        Broadband::create([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'renewal_date' => $request->renewal_date,
        ]);

        return to_route('broadbands.index')->with('success_message', $request->name . ' Has been Added!');
    }

    public function show(Broadband $broadband)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('view', $broadband))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to update Broadband.');

        }

        // Return the Create View to the browser
        return view('broadband.show', [
            "broadband" => $broadband,
        ]);
    }

    public function edit(Broadband $broadband)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('update', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to update Broadband.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('software.edit', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
            "broadbands" => $broadband,
        ]);
    }

    public function update(Request $request, Broadband $broadband)
    {
        //Check to see if the user has permission to update software on the system
        if(auth()->user()->cant('update', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Update Broadband.');

        }

        //Validate the post data
        $request->validate([
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'purchased_date' => 'required|date',
            'renewal_date' => 'required|date',
        ]);

        $broadband->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'renewal_date' => $request->renewal_date,
        ]);

        return to_route('broadbands.index')->with('success_message', $request->name . ' Has been Updated!');
    }

    public function recycleBin()
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('delete', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Delete Broadband.');

        }
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');

        // Return the Create View to the browser
        return view('broadband.bin', [
            "locations" => $locations,
            "broadbands" => Broadband::onlyTrashed()->paginate(),
        ]);

    }

    public function destroy(Broadband $broadband)
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('recycleBin', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Archive Broadband.');

        }
        $broadband->delete();

        return to_route('broadbands.index')->with('success_message', $broadband->name . ' Has been sent to the recycle bin!');

    }

    public function restore($id)
    {
        //Find the software (withTrashed needed)
        $broadband = Broadband::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the software
        if(auth()->user()->cant('delete', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Restore Broadband.');

        }

        //Restores the software
        $broadband->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $broadband->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('broadbands.index');
    }

    public function forceDelete($id)
    {
        //Find the software (withTrashed needed)
        $broadband = Broadband::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the software
        if(auth()->user()->cant('delete', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Delete Broadband.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $broadband->name;
        //Force Delete removes the model permanently from the system
        $broadband->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('broadband.bin');
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

        $broadband = Broadband::find($request->broadband_id);
        $broadband->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('broadbands.show', $broadband->id);
    }

    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised | Download of Broadband Information Report.');

        }
        $broadband = array();
        $found = Software::select('name', 'id', 'renewal_date', 'supplier_id', 'purchased_date', 'purchased_cost', 'location_id', 'created_at')->withTrashed()->whereIn('id', json_decode($request->software))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['renewal_date'] = \Carbon\Carbon::parse($f->renewal_date)->format('d/m/Y') ?? 'N/A';
            $array['supplier'] = $f->supplier->name ?? 'No Supplier';
            $broadband[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'properties-report-' . $date;
        BroadbandsPdf::dispatch($broadband, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('broadbands.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Broadband $broadband)
    {
        if(auth()->user()->cant('view', $broadband))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised | Download of Broadband Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "software-{$broadband->id}-{$date}";
        BroadbandPdf::dispatch($broadband, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('broadbands.show', $broadband->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised | Import Broadband.');

        }
        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(
            //checks for spelling and if there present for any allowed heading in the csv.
            isset($values['name']) && isset($values['supplier_id']) && isset($values['location_id'])
            && isset($values['renewal_date']) && isset($values['purchased_date']) && isset($values['purchased_cost'])
        )
        {
        } else
        {
            return to_route('broadbands.index')->with('danger_message', "CSV Heading's Incorrect Please amend and try again!");
        }
        //headings incorrect end
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new BroadbandImport;
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

                return view('broadband.importErrros', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                    "suppliers" => Supplier::all(),
                ]);

            } else
            {
                return to_route('broadbands.index')->with('success_message', 'All Broadband`s were imported correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('broadbands.index');
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
            "renewal_date.*" => "required",
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
                $software->renewal_date = $request->renewal_date[$i];
                $software->save();
            }

            session()->flash('success_message', 'You have successfully added all Broadband Items!');

            return 'Success';
        }
    }

    ////////////////////////////////////////////////////////
    ///////////////Export Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised | Export Broadband Information.');

        }
        $broadbands = Broadband::withTrashed()->whereIn('id', json_decode($request->broadband))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new BroadbandExport($broadbands), "/public/csv/broadband-{$date}.xlsx");
        $url = asset("storage/csv/broadband-{$date}.xlsx");

        return to_route('broadbands.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        if(auth()->user()->cant('viewAll', Broadband::class))
        {
            return ErrorController::forbidden(to_route('broadbands.index'), 'Unauthorised to Export Broadband Errors.');
        }
        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new BroadbandErrorsExport($export), "/public/csv/broadband-errors-{$date}.csv");
        $url = asset("storage/csv/broadband-errors-{$date}.csv");

        return to_route('broadbands.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }
    
}
