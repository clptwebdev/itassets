<?php

namespace App\Http\Controllers;

use App\Models\AUC;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;

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

        //Else find the locations that the user has been assigned to
        $locations = auth()->user()->locations->select('id', 'name')->withCount('aucs')->get();

        //Find the properties that are assigned to the locations the User has permissions to.
        $limit = session('auc_limit') ?? 25;
        $aucs = AUC::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');

        //No filter is set so set the Filter Session to False - this is to display the filter if is set
        session(['auc_filter' => false]);

        return view('auc.view', [
            "aucs" => $aucs,
            "locations" => $locations,
        ]);
    }

    public function show(AUC $auc)
    {
        if(auth()->user()->cant('view', AUC::class))
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
        return view('auc.create', [
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
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0',
        ]);

        $property = new AUC;

        $property->fill([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'value' => $request->value,
            'depreciation' => $request->depreciation,
            'type' => $request->type,
            'date' => $request->date,
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

        return view('auc.edit', compact('auc'));
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
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0',
        ]);

        //Fill the Model fields from the request
        $auc->fill($request->only('name', 'location_id', 'value', 'depreciation', 'type'))->save();

        //Return the session message to the index
        session()->flash('success_message', $request->name . ' has been updated successfully');

        //return to the view
        return to_route('aucs.index');

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

        return view('auc.bin', [
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

            session(['auc_amount' => $request->amount]);
        }

        //Check the Users Locations Permissions
        $locations = Location::select('id', 'name')->withCount('aocs')->get();

        $auc = AUC::locationFilter($locations->pluck('id'));

        if(session()->has('auc_locations'))
        {
            $auc->locationFilter(session('auc_locations'));
            session(['auc_filter' => true]);
        }

        if(session()->has('auc_start') && session()->has('auc_end'))
        {
            $auc->purchaseFilter(session('auc_start'), session('auc_end'));
            session(['auc_filter' => true]);
        }

        if(session()->has('auc_amount'))
        {
            $auc->costFilter(session('auc_amount'));
            session(['auc_filter' => true]);
        }

        if(session()->has('auc_search'))
        {
            $auc->searchFilter(session('auc_search'));
            session(['auc_filter' => true]);
        }

        $auc->leftJoin('locations', 'auc.location_id', '=', 'locations.id')
            ->orderBy(session('auc_orderby') ?? 'date', session('auc_direction') ?? 'asc')
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
        session()->forget(['property_filter', 'property_locations', 'property_start', 'property_end', 'property_amount', 'property_search']);

        return to_route('property.index');
    }

}
