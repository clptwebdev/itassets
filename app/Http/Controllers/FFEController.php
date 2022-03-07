<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\FFE;

class FFEController extends Controller
{
    //FFE = Furniture, Fixtures and Equipment
   
    ////////////////////////////////////////////
    ////////////// View Functions ////////////
    ////////////////////////////////////////////
    
    public function index()
    {
         //Check to see if the User has permission to View All the AUC. 
         if(auth()->user()->cant('viewAll', FFE::class))
         {
             return redirect(route('errors.forbidden', ['area', 'FFE', 'view']));
         }
 
         //Check to see if the user has SUper Admin Role
         if(auth()->user()->role_id == 1)
         {
             //If the User has Super Admin Role/Correct Permissions find all the locations
             $locations = Location::select('id', 'name')->withCount('ffe')->get();
         } else
         {
             //Else find the locations that the user has been assigned to
             $locations = auth()->user()->locations->select('id', 'name')->withCount('ffe')->get();
         }
 
         //Find the properties that are assigned to the locations the User has permissions to.
         $limit = session('ffe_limit') ?? 25;
         $ffes = FFE::locationFilter($locations->pluck('id')->toArray())->paginate(intval($limit))->fragment('table');
 
         //No filter is set so set the Filter Session to False - this is to display the filter if is set
         session(['fefe_filter' => false]);
 
         return view('ffe.view', [
             "ffes" => $ffes,
             "locations" => $locations,
         ]);
    }

    ////////////////////////////////////////////
    ////////////// Create Functions ////////////
    ////////////////////////////////////////////

    public function create()
    {

        //Check to see if the User is has permission to create an AUC
        if(auth()->user()->cant('create', FFE::class))
        {
            return redirect(route('errors.forbidden', ['area', 'FFE', 'create']));
        }

        //Get the Locations that the user has permission for
        if(auth()->user()->role_id == 1)
        {
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;
        }

        // Return the Create View to the browser
        return view('ffe.create', [
            "locations" => $locations
        ]);
    }

    public function store(Request $request)
    {
        //Store the new property in the database

        //Check to see if the user has permission to add nw property on the system
        if(auth()->user()->cant('create', AUC::class))
        {
            return redirect(route('errors.forbidden', ['area', 'AUC', 'create']));
        }

        //Validate the post data
        $validation = $request->validate([
            'name' => 'required',
            'location_id' => 'required',
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'depreciation' => 'required|numeric',
            'type' => 'required|gt:0'
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

        return redirect(route('aucs.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
