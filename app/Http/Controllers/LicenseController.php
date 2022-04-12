<?php

namespace App\Http\Controllers;

use App\Exports\LicenseErrorsExport;
use App\Exports\LicenseExport;
use App\Imports\LicenseImport;
use App\Jobs\licensePdf;
use App\Jobs\licensesPdf;
use App\Models\License;
use App\Models\Location;
use App\Models\Report;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\HeadingRowImport;
use PDF;

class LicenseController extends Controller {

    public function index()
    {
        //Check to see if the User has permission to View All the Software.
        if(auth()->user()->cant('viewAll', License::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised to View License.');

        }

        // find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');
        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('property_limit') ?? 25;
        $licenses = License::locationFilter($locations->pluck('id')->toArray())->latest('expiry', '>=', Carbon::parse(now()))->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['property_filter' => false]);

        return view('licenses.view', [
            "licenses" => $licenses,
            "locations" => $locations,
        ]);
    }

    public function create()
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('create', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Create License.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('licenses.create', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        //Check to see if the user has permission to add new software on the system

        if(auth()->user()->cant('create', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Store License.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'contact' => 'nullable|email:rfc,dns,filter',
            'expiry_date' => 'nullable|date',
        ]);

        License::create([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'contact' => $request->contact,
            'expiry' => $request->expiry_date,
        ]);

        return to_route('licenses.index')->with('success_message', $request->name . ' Has been Added!');
    }

    public function show(License $license)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('view', $license))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to update License.');

        }

        // Return the Create View to the browser
        return view('licenses.show', [
            "license" => $license,
        ]);
    }

    public function edit(License $license)
    {
        //Check to see if the User is has permission to create
        if(auth()->user()->cant('update', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to update License.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('licenses.edit', [
            "locations" => $locations,
            "suppliers" => Supplier::all(),
            "license" => $license,
        ]);
    }

    public function update(Request $request, License $license)
    {
        //Check to see if the user has permission to update software on the system
        if(auth()->user()->cant('update', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Update License.');

        }

        //Validate the post data
        $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'supplier_id' => 'required',
            'purchased_cost' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'contact' => 'nullable|email:rfc,dns,filter',
            'expiry_date' => 'nullable',
        ]);

        $license->update([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'contact' => $request->contact,
            'expiry' => $request->expiry_date,
        ]);

        return to_route('licenses.index')->with('success_message', $request->name . ' Has been Updated!');
    }

    public function recycleBin()
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('delete', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Delete License.');

        }
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name');

        // Return the Create View to the browser
        return view('licenses.bin', [
            "locations" => $locations,
            "licenses" => License::onlyTrashed()->paginate(),
        ]);

    }

    public function destroy(License $license)
    {
        //Check to see if the user has permission to delete software on the system
        if(auth()->user()->cant('recycleBin', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Archive License.');

        }
        $license->delete();

        return to_route('licenses.index')->with('success_message', $license->name . ' Has been sent to the recycle bin!');

    }

    public function restore($id)
    {
        //Find the software (withTrashed needed)
        $license = License::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the software
        if(auth()->user()->cant('delete', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Restore License.');

        }

        //Restores the software
        $license->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $license->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('licenses.index');
    }

    public function forceDelete($id)
    {
        //Find the software (withTrashed needed)
        $license = License::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the software
        if(auth()->user()->cant('delete', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Delete License.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $license->name;
        //Force Delete removes the model permanently from the system
        $license->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('license.bin');
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

        $license = License::find($request->license_id);
        $license->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('licenses.show', $license->id);
    }

    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised | Download of License Information Report.');

        }
        $licenses = array();
        $found = License::select('name', 'id', 'expiry', 'contact', 'supplier_id', 'purchased_cost', 'location_id', 'created_at')->withTrashed()->whereIn('id', json_decode($request->license))->with('location')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['purchased_cost'] = $f->purchased_cost;
            $array['expiry'] = \Carbon\Carbon::parse($f->expiry)->format('d/m/Y') ?? 'N/A';
            $array['contact'] = $f->contact ?? 'N/A';
            $array['supplier'] = $f->supplier->name ?? 'No Supplier';
            $licenses[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'License-report-' . $date;
        licensesPdf::dispatch($licenses, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('licenses.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(License $license)
    {
        if(auth()->user()->cant('view', $license))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised | Download of License Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "License-{$license->id}-{$date}";
        LicensePdf::dispatch($license, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('licenses.show', $license->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised | Import License.');

        }
        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(
            //checks for spelling and if there present for any allowed heading in the csv.
            isset($values['name']) && isset($values['supplier_id']) && isset($values['location_id'])
            && isset($values['expiry']) && isset($values['purchased_cost']) && isset($values['contact'])
        )
        {
        } else
        {
            return to_route('licenses.index')->with('danger_message', "CSV Heading's Incorrect Please amend and try again!");
        }
        //headings incorrect end
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new LicenseImport;
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

                return view('licenses.importErrros', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                    "suppliers" => Supplier::all(),
                ]);

            } else
            {
                return to_route('licenses.index')->with('success_message', 'All License`s were imported correctly!');

            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

            return to_route('licenses.index');
        }


    }

    public function importErrors(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "name.*" => "max:255|required",
            'location_id.*' => 'required|gt:0',
            'supplier_id.*' => 'required|gt:0',
            'purchased_cost.*' => 'required|nullable',
            "expiry.*" => "nullable",
            "contact.*" => "nullable|email:rfc,dns,filter",
        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                $license = new License;
                $license->name = $request->name[$i];
                $license->supplier_id = $request->supplier_id[$i];
                $license->purchased_cost = $request->purchased_cost[$i];
                $license->location_id = $request->location_id[$i];
                $license->expiry = $request->expiry[$i];
                $license->contact = $request->contact[$i];
                $license->save();
            }

            session()->flash('success_message', 'You have successfully added all License Items!');

            return 'Success';
        }
    }

    ////////////////////////////////////////////////////////
    ///////////////Export Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised | Export License Information.');

        }
        $licenses = License::withTrashed()->whereIn('id', json_decode($request->license))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new LicenseExport($licenses), "/public/csv/License-{$date}.xlsx");
        $url = asset("storage/csv/License-{$date}.xlsx");

        return to_route('licenses.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        if(auth()->user()->cant('viewAll', License::class))
        {
            return ErrorController::forbidden(route('licenses.index'), 'Unauthorised to Export License Errors.');
        }
        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new LicenseErrorsExport($export), "/public/csv/License-errors-{$date}.csv");
        $url = asset("storage/csv/License-errors-{$date}.csv");

        return to_route('licenses.index')
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
            //gets the first license for this location with the furthest renewal date
            if($location->license)
            {
                $license = $location->license->sortByDesc(function() {
                    return 'expiry';
                })->where('expiry', '>=', $dateNow)->first();
            }
            if($license ?? null)
            {
                $expiry = Carbon::parse($license->expiry);
                //30 days
                if($expiry == Carbon::today()->addDays($days->value ?? 30))
                {
                    //send you have 30 day warning
                    foreach($it_managers as $user)
                    {

                        Mail::to($user->email)->send(new \App\Mail\LicenseExpiry($days->value ?? 30, $license));
                    }
                }
                //14 days
                if($expiry == Carbon::today()->addDays(14))
                {
                    //send you have 1 day warning
                    foreach($it_managers as $user)
                    {

                        Mail::to($user->email)->send(new \App\Mail\LicenseExpiry('14', $license));
                    }
                }
                //7 days
                if($expiry == Carbon::today()->addDays(7))
                {
                    //send you have 7 day warning
                    foreach($it_managers as $user)
                    {

                        Mail::to($user->email)->send(new \App\Mail\LicenseExpiry('7', $license));
                    }
                }
                //On day days
                if($expiry == Carbon::today())
                {
                    //send you have 0 days Left
                    foreach($it_managers as $user)
                    {
                        Mail::to($user->email)->send(new \App\Mail\LicenseExpiry('0', $license));
                    }
                }
            }
        }
    }

}
