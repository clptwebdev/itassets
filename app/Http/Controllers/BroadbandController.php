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
use App\Models\Role;
use App\Models\Setting;
use App\Models\Software;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use PDF;

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
        //Find the properties that are asigned to thes locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $broadbands = Broadband::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');
        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        $now = \Carbon\Carbon::now();
        $startDate = \Carbon\Carbon::parse('09/01/' . $now->format('Y'));
        $nextYear = \Carbon\Carbon::now()->addYear()->format('Y');
        $nextStartDate = \Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->addYear()->format('Y'));
        $endDate = \Carbon\Carbon::parse('08/31/' . $nextYear);
        if(! $startDate->isPast())
        {
            $startDate->subYear();
            $endDate->subYear();
            $nextStartDate->subYear();
        }

        $currentCost = Broadband::locationFilter($locations->pluck('id')->toArray())->whereYear('purchased_date', Carbon::now()->format('Y'))->sum('purchased_cost');
        $previousCost = Broadband::locationFilter($locations->pluck('id')->toArray())->whereYear('purchased_date', Carbon::now()->subYear()->format('Y'))->sum('purchased_cost');

        return view('broadband.view', [
            'previous_cost' => $previousCost,
            'current_cost' => $currentCost,
            "broadbands" => $broadbands,
            "locations" => $locations->get(),
        ]);
    }

    public function create()
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('create', Broadband::class))
        {
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Create Broadband.');

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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Store Broadband.');

        }

        //Validate the post data
        $validation = $request->validate([
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'purchased_date' => 'required|date',
            'renewal_date' => 'required|date',
            'package' => 'required',
        ]);

        Broadband::create([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'renewal_date' => $request->renewal_date,
            'package' => $request->package,
        ]);

        return to_route('broadbands.index')->with('success_message', $request->name . ' Has been Added!');
    }

    public function show(Broadband $broadband)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('view', $broadband))
        {
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to update Broadband.');

        }
        // find the locations that the user has been assigned to

        $broadbands = Broadband::whereLocationId($broadband->location->id)->get()->except($broadband->id);

        // Return the Create View to the browser
        return view('broadband.show', [
            "broadbands" => $broadbands,
            "broadband" => $broadband,
        ]);
    }

    public function edit(Broadband $broadband)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('update', Broadband::class))
        {
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to update Broadband.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('broadband.edit', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
            "broadband" => $broadband,
        ]);
    }

    public function update(Request $request, Broadband $broadband)
    {
        //Check to see if the user has permission to update software on the system
        if(auth()->user()->cant('update', Broadband::class))
        {
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Update Broadband.');

        }

        //Validate the post data
        $request->validate([
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'purchased_date' => 'required|date',
            'renewal_date' => 'required|date',
            'package' => 'required',
        ]);

        $broadband->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'purchased_date' => $request->purchased_date,
            'renewal_date' => $request->renewal_date,
            'package' => $request->package,
        ]);

        return to_route('broadbands.index')->with('success_message', $request->name . ' Has been Updated!');
    }

    public function recycleBin()
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('delete', Broadband::class))
        {
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Delete Broadband.');

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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Archive Broadband.');

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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Restore Broadband.');

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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Delete Broadband.');

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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised | Download of Broadband Information Report.');

        }
        $broadbands = array();
        $found = Broadband::select('name', 'id', 'renewal_date', 'package', 'supplier_id', 'purchased_date', 'purchased_cost', 'location_id', 'created_at')->withTrashed()->whereIn('id', json_decode($request->broadband))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['renewal_date'] = \Carbon\Carbon::parse($f->renewal_date)->format('d/m/Y') ?? 'N/A';
            $array['package'] = $f->package ?? 'N/A';
            $array['supplier'] = $f->supplier->name ?? 'No Supplier';
            $broadbands[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'Broadband-report-' . $date;
        BroadbandsPdf::dispatch($broadbands, $user, $path)->afterResponse();
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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised | Download of Broadband Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "Broadband-{$broadband->id}-{$date}";
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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised | Import Broadband.');

        }
        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(
            //checks for spelling and if there present for any allowed heading in the csv.
            isset($values['name']) && isset($values['supplier_id']) && isset($values['location_id'])
            && isset($values['renewal_date']) && isset($values['purchased_date']) && isset($values['purchased_cost'])
            && isset($values['package'])
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
            "name.*" => "max:255",
            'location_id.*' => 'required|gt:0',
            'supplier_id.*' => 'required|gt:0',
            'purchased_date.*' => 'required|date',
            'purchased_cost.*' => 'required',
            "renewal_date.*" => "required",
            "package.*" => "required",
        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                $broadband = new Broadband();
                $broadband->name = $request->name[$i];
                $broadband->supplier_id = $request->supplier_id[$i];
                $broadband->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                $broadband->purchased_cost = $request->purchased_cost[$i];
                $broadband->location_id = $request->location_id[$i];
                $broadband->renewal_date = $request->renewal_date[$i];
                $broadband->package = $request->package[$i];
                $broadband->save();
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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised | Export Broadband Information.');

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
            return ErrorController::forbidden(route('broadbands.index'), 'Unauthorised to Export Broadband Errors.');
        }
        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new BroadbandErrorsExport($export), "/public/csv/broadband-errors-{$date}.csv");
        $url = asset("storage/csv/broadband-errors-{$date}.csv");

        return to_route('broadbands.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function expired()
    {
        $dateNow = Carbon::today();
        $it_managers = User::itManager();
        $days = Setting::whereName('broadband_expiry')->first();
        foreach(Location::all() as $location)
        {
            //gets the first broadband for this location with the furthest renewal date
            $broadband = $location->broadband->sortByDesc(function() {
                return 'renewal_date';
            })->where('renewal_date', '>=', $dateNow)->first();
            if($broadband)
            {
                $renewalDate = Carbon::parse($broadband->renewal_date);
                //30 days
                if($renewalDate == Carbon::today()->addDays($days->value ?? 30))
                {
                    //send you have 30 day warning
                    foreach($it_managers as $user)
                    {

                        Mail::to($user->email)->send(new \App\Mail\BroadbandExpiry($days->value ?? 30, $broadband));
                    }
                }
                //14 days
                if($renewalDate == Carbon::today()->addDays(14))
                {
                    //send you have 1 day warning
                    foreach($it_managers as $user)
                    {

                        Mail::to($user->email)->send(new \App\Mail\BroadbandExpiry('14', $broadband));
                    }
                }
                //7 days
                if($renewalDate == Carbon::today()->addDays(7))
                {
                    //send you have 1 day warning
                    foreach($it_managers as $user)
                    {

                        Mail::to($user->email)->send(new \App\Mail\BroadbandExpiry('7', $broadband));
                    }
                }
                //On day days
                if($renewalDate == Carbon::today())
                {
                    //send you have 0 days Left
                    foreach($it_managers as $user)
                    {
                        Mail::to($user->email)->send(new \App\Mail\BroadbandExpiry('0', $broadband));
                    }
                }
            }
        }
    }

}
