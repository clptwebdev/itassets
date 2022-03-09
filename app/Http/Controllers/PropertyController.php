<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller {

    ////////////////////////////////////////////
    ////////////// View Functions ////////////
    ////////////////////////////////////////////

    public function index()
    {
        //Check to see if the User has permission to View All the Properties.
        if(auth()->user()->cant('viewAll', Property::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Properties.');

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
        if(auth()->user()->cant('view', Property::class))
        {
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Show Properties.');

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
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Create Properties.');

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
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Store Properties.');

        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0',
        ]);

        $property = new Property;

        $property->fill([
            'name' => $request->name,
            'location_id' => $request->location_id,
            'value' => $request->value,
            'depreciation' => $request->depreciation,
            'type' => $request->type,
            'date' => $request->date,
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
        if(auth()->user()->cant('edit', Property::class))
        {
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Edit Properties.');

        }

        return view('property.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        // Check to see whether the user has permission to edit the selected property
        if(auth()->user()->cant('update', Property::class))
        {
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Update Properties.');

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
        $property->fill($request->only('name', 'location_id', 'value', 'depreciation', 'type'))->save();

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
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Delete Properties.');

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
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Recycle Properties.');

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
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Restore Properties.');

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
            return ErrorController::forbidden(to_route('properties.index'), 'Unauthorised to Delete Properties.');

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

            session(['property_amount' => $request->amount]);
        }
        //Check the Users Locations Permissions
        $locations = Location::select('id', 'name')->withCount('property')->get();

        $property = Property::locationFilter($locations->pluck('id'));

        if(session()->has('property_locations'))
        {
            $property->locationFilter(session('property_locations'));
            session(['property_filter' => true]);
        }

        if(session()->has('property_start') && session()->has('property_end'))
        {
            $property->purchaseFilter(session('property_start'), session('property_end'));
            session(['property_filter' => true]);
        }

        if(session()->has('property_amount'))
        {
            $property->costFilter(session('property_amount'));
            session(['property_filter' => true]);
        }

        if(session()->has('property_search'))
        {
            $property->searchFilter(session('property_search'));
            session(['property_filter' => true]);
        }

        $property->leftJoin('locations', 'properties.location_id', '=', 'locations.id')
            ->orderBy(session('property_orderby') ?? 'date', session('property_direction') ?? 'asc')
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
        session()->forget(['property_filter', 'property_locations', 'property_start', 'property_end', 'property_amount', 'property_search']);

        return to_route('property.index');
    }

}
