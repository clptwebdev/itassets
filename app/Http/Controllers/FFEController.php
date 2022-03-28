<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\FFE;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Status;

class FFEController extends Controller {

    //FFE = Furniture, Fixtures and Equipment

    ////////////////////////////////////////////
    ////////////// View Functions ////////////
    ////////////////////////////////////////////

    public function index()
    {
        //Check to see if the User has permission to View All the AUC.
        if(auth()->user()->cant('viewAll', FFE::class))
        {
            return to_route('errors.forbidden', ['area', 'FFE', 'view']);
        }

        //Find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('ffe')->get();

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

    public function show(FFE $ffe)
    {
        return view('FFE.show', compact('ffe'));
    }

    ////////////////////////////////////////////
    ////////////// Create Functions ////////////
    ////////////////////////////////////////////

    public function create()
    {

        //Check to see if the User is has permission to create an AUC
        if(auth()->user()->cant('create', FFE::class))
        {
            return to_route('errors.forbidden', ['area', 'FFE', 'create']);
        }

        //Get the Locations that the user has permission for
        if(auth()->user()->role_id == 1)
        {
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;
        }

        $manufacturers = Manufacturer::all();
        $suppliers = Supplier::all();
        $statuses = Status::all();
        // Return the Create View to the browser
        return view('ffe.create', [
            "locations" => $locations,
            "manufacturers" => $manufacturers,
            "suppliers" => $suppliers,
            "statuses" => $statuses,
        ]);
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', FFE::class))
        {
            return ErrorController::forbidden(to_route('ffes.index'), 'Unauthorised to Create Furniture, Fixtures and Equipment.');

        }

        $request->validate([
            "name" => "required|max:255",
            "supplier_id" => "nullable",
            "location_id" => "required",
            "room" => "nullable",
            "notes" => "nullable",
            "status_id" => "nullable",
            'order_no' => 'nullable',
            'serial_no' => 'required',
            'warranty' => 'int|nullable',
            'purchased_date' => 'nullable|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);


        $ffe = FFE::create(array_merge($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation'
        ), ['user_id' => auth()->user()->id]));
        $ffe->category()->attach(explode(',', $request->category));

        return to_route("ffes.index")->with('success_message', $request->name . 'has been successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
