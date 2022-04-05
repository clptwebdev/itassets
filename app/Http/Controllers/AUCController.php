<?php

namespace App\Http\Controllers;

use App\Models\AUC;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;

//Jobs
use App\Jobs\AUCSPdf;
use App\Jobs\AUCPdf;
//Models
use App\Models\Report;

//Imports
use App\Imports\AUCImport;

//Exports
use App\Exports\AUCExport;
use App\Exports\AUCErrorsExport;

use Illuminate\Support\Facades\Validator;

use App\Rules\permittedLocation;
use App\Rules\findLocation;

use \Carbon\Carbon;

class AUCController extends Controller {

    //AUC = Assets Under Construction

    ////////////////////////////////////////////
    ////////////// View Functions ////////////
    ////////////////////////////////////////////

    public function index()
    {
        //Check to see if the User has permission to View All the AUC.
        if(auth()->user()->cant('viewAll', AUC::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Assets Under Construction.');

        }

        if(session()->has('auc_filter') && session('auc_filter') === true){
            return to_route('auc.filtered');
        }

        //Find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('auc')->get();

        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('auc_limit') ?? 25;
        $aucs = AUC::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['auc_filter' => false]);

        return view('AUC.view', [
            "aucs" => $aucs,
            "locations" => $locations,
        ]);
    }

    public function show(AUC $auc)
    {
        if(auth()->user()->cant('view', $auc))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Show Assets Under Construction.');

        }

        //This function returns the property and displays the information about it on the View
        return view('AUC.show', compact('auc'));
    }

    ////////////////////////////////////////////
    ////////////// Create Functions ////////////
    ////////////////////////////////////////////

    public function create()
    {

        //Check to see if the User is has permission to create an AUC
        if(auth()->user()->cant('create', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Create Assets Under Construction.');

        }

        //Get the Locations that the user has permission for
        $locations = auth()->user()->locations;

        // Return the Create View to the browser
        return view('AUC.create', [
            "locations" => $locations,
        ]);
    }

    public function store(Request $request)
    {
        //Store the new property in the database

        //Check to see if the user has permission to add nw property on the system
        if(auth()->user()->cant('create', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Store Assets Under Construction.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0',
            'user_id' => auth()->user()->id,
        ]);

        $property = new AUC;

        $property->fill([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'purchased_cost' => $request->purchased_cost,
            'depreciation' => $request->depreciation,
            'type' => $request->type,
            'purchased_date' => $request->purchased_date,
        ])->save();

        session()->flash('success_message', $request->name . ' has been created successfully');

        return to_route('aucs.index');
    }


    ////////////////////////////////////////////
    ////////////// Update Functions ////////////
    ////////////////////////////////////////////

    public function edit(AUC $auc)
    {
        // Check to see whether the user has permission to edit the sleected property
        if(auth()->user()->cant('update', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Edit Assets Under Construction.');

        }

        return view('AUC.edit', compact('auc'));
    }

    public function update(Request $request, AUC $auc)
    {
        // Check to see whether the user has permission to edit the sleected property
        if(auth()->user()->cant('update', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Update Assets Under Construction.');

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
        $auc->fill($request->only('name', 'location_id', 'purchased_cost', 'purchased_date', 'depreciation', 'type'))->save();

        //Return the session message to the index
        session()->flash('success_message', $request->name . ' has been updated successfully');

        //return to the view
        return to_route('aucs.index');

    }

    public function move(AUC $auc)
    {
        //Moving the Asset Under Construction to the Property
        $property = new Property;
        $property->fill([
            'name' => $auc->name,
            'type' => $auc->type,
            'purchased_cost' => $auc->purchased_cost,
            'purchased_date' => $auc->purchased_date,
            'depreciation' => $auc->depreciation,
            'location_id' => $auc->location_id,
        ]);
        $property->save();

        foreach($auc->comment as $comment){
            $property->comment()->create(['title' => $comment->title, 'comment' => $comment->comment, 'user_id' => $comment->user_id]);
            $comment->delete();
        }

        $auc->forceDelete();

        session()->flash('success_message', 'You have moved the Asset-Under-Construction to Properties');

        return to_route('properties.index');
    }

    ////////////////////////////////////////////
    ////////////// Delete Functions ////////////
    ////////////////////////////////////////////

    public function destroy(AUC $auc)
    {
        //Check to see whether the User has permissions to remove the collection or send it to the Recycle Bin
        if(auth()->user()->cant('delete', $auc))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Delete Assets Under Construction.');

        }

        $name = $auc->name;

        $auc->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return to_route('aucs.index');
    }

    public function recycleBin()
    {
        //Check to see if the users have permissions to view the recycle bin
        if(auth()->user()->cant('delete', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Recycle Assets Under Construction.');

        }

        $limit = session('auc_limit') ?? 25;

        //Check the User Location Permissions

        $aucs = auth()->user()->location_auc()->onlyTrashed()->paginate(intval($limit))->fragment('table');
        $locations = auth()->user()->locations;

        return view('AUC.bin', [
            "aucs" => $aucs,
            "locations" => $locations,
        ]);
    }

    public function restore($id)
    {
        //Find the Property (withTrashed needed)
        $auc = AUC::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the property
        if(auth()->user()->cant('delete', $auc))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Restore Assets Under Construction.');

        }

        //Restores the Property
        $auc->restore();

        //Session message to be sent ot the View page (This is where the model will now appear)
        session()->flash('success_message', $auc->name . ' has been restored.');

        //Redirect ot the model view
        return to_route('aucs.index');
    }

    public function forceDelete($id)
    {
        //Find the Collection (withTrashed needed)
        $auc = AUC::withTrashed()->where('id', $id)->first();

        //Check to see if the user has permission to restore the Collection
        if(auth()->user()->cant('delete', $auc))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Delete Assets Under Construction.');

        }
        //Assign the name to a variable else will not be able to reference the name in hte session flash
        $name = $auc->name;
        //Force Delete removes the Collection permanently from the system
        $auc->forceDelete();
        //Session message to be sent ot the Recycle Bin page
        session()->flash('danger_message', $name . ' was deleted permanently');

        //redirect back to the recycle bin
        return to_route('auc.bin');
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
                session(['auc_search' => $request->search]);
            }

            if(! empty($request->limit))
            {
                session(['auc_limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);

                session(['auc_orderby' => $array[0]]);
                session(['auc_direction' => $array[1]]);

            }

            if(! empty($request->locations))
            {
                session(['auc_locations' => $request->locations]);
            }

            if($request->start != '' && $request->end != '')
            {
                session(['auc_start' => $request->start]);
                session(['auc_end' => $request->end]);
            }

            session(['auc_min' => $request->minCost]);
            session(['auc_max' => $request->maxCost]);
        }

        //Find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('auc')->get();

        $auc = AUC::locationFilter($locations->pluck('id'));

        if(session()->has('auc_locations'))
        {
            $auc->locationFilter(session('auc_locations'));
            session(['auc_filter' => true]);
        }

        if(session()->has('auc_start') && session()->has('auc_end'))
        {
            $auc->purchaseFilter(Carbon::parse(session('auc_start')), Carbon::parse(session('auc_end')));
            session(['auc_filter' => true]);
        }

        if(session()->has('auc_min') && session()->has('auc_max'))
        {
            $auc->costFilter(session('auc_min'), session('auc_max'));
            session(['auc_filter' => true]);

        }

        if(session()->has('auc_search'))
        {
            $auc->searchFilter(session('auc_search'));
            session(['auc_filter' => true]);
        }

        $auc->leftJoin('locations', 'a_u_c_s.location_id', '=', 'locations.id')
            ->orderBy(session('auc_orderby') ?? 'purchased_date', session('auc_direction') ?? 'asc')
            ->select('a_u_c_s.*', 'locations.name as location_name');
        $limit = session('auc_limit') ?? 25;

        return view('auc.view', [
            "aucs" => $auc->paginate(intval($limit))->withPath(asset('/auc/filter'))->fragment('table'),
            "locations" => $locations,
        ]);
    }

    public function clearFilter()
    {
        //Clear the Filters for the properties
        session()->forget(['auc_filter', 'auc_locations', 'auc_start', 'auc_end', 'auc_min', 'auc_max', 'auc_search']);

        return to_route('aucs.index');
    }

     ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////


    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', AUC::class))
        {
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised | Download of Assets Under Construction Report.');

        }
        $aucs = array();
        $found = AUC::select('name', 'id', 'depreciation', 'type', 'purchased_date', 'purchased_cost', 'location_id')->withTrashed()->whereIn('id', json_decode($request->aucs))->with('location')->get();
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
            $aucs[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = 'auc-report-' . $date;

        AUCSPdf::dispatch($aucs, $user, $path)->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('aucs.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(AUC $auc)
    {
        if(auth()->user()->cant('generateShowPDF', $auc))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised | Download of Property Information.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('dmyHis');
        $path = "aucs-{$auc->id}-{$date}";
        AUCPdf::dispatch($auc, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('aucs.show', $auc->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    ////////////////////////////////////////////////////////
    ///////////////Import Functions/////////////////////////
    ////////////////////////////////////////////////////////

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised | Import Properties.');

        }
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new AUCImport;
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

                return view('AUC.importErrors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "locations" => auth()->user()->locations,
                ]);

            } else
            {
                return to_route('aucs.index')->with('success_message', 'All Properties were imported correctly!');

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

    public function exportImportErrors(Request $request)
    {
        $export = $request['name'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);

        if(auth()->user()->cant('viewAll', AUC::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised to Export AUC Errors.');

        }

        $date = \Carbon\Carbon::now()->format('dmyHis');
        \Maatwebsite\Excel\Facades\Excel::store(new AUCErrorsExport($export), "/public/csv/aucs-errors-{$date}.csv");
        $url = asset("storage/csv/aucs-errors-{$date}.csv");

        return to_route('aucs.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Property::class))
        {
            return ErrorController::forbidden(to_route('aucs.index'), 'Unauthorised | Export AUC Information.');

        }
        $aucs = AUC::withTrashed()->whereIn('id', json_decode($request->aucs))->with('location')->get();
        $date = \Carbon\Carbon::now()->format('dmyHi');
        \Maatwebsite\Excel\Facades\Excel::store(new AUCExport($aucs), "/public/csv/aucs-{$date}.xlsx");
        $url = asset("storage/csv/aucs-{$date}.xlsx");

        return to_route('aucs.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

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

        $auc = AUC::find($request->auc_id);
        $auc->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('aucs.show', $auc->id);
    }


}
