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
        session(['ffe_filter' => false]);

        return view('FFE.view', [
            "ffes" => $ffes,
            "locations" => $locations,
        ]);
    }

    public function show(FFE $ffe)
    {
        return view('FFE.show', compact('ffe'));
    }

    public function recycleBin()
    {
        if(auth()->user()->cant('viewAll', FFE::class))
        {
            return ErrorController::forbidden(route('ffes.index'), 'Unauthorised | View FFE Recycle Bin.');

        }
        $ffes = auth()->user()->location_ffes()->onlyTrashed()->get();

        return view('FFE.bin', compact('ffes'));
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

        //Find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('ffe')->get();

        $manufacturers = Manufacturer::all();
        $suppliers = Supplier::all();
        $statuses = Status::all();
        // Return the Create View to the browser
        return view('FFE.create', [
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
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ), ['user_id' => auth()->user()->id]));

        if($request->category != '' && !empty(explode(',', $request->category))){
            $ffe->category()->attach(explode(',', $request->category));
        }

        return to_route("ffes.index")->with('success_message', $request->name . 'has been successfully created!');
    }

    ////////////////////////////////////////////
    ////////////// Update Functions ////////////
    ////////////////////////////////////////////

    public function edit(FFE $ffe)
    {
        //Check to see if the User is has permission to create an AUC
        if(auth()->user()->cant('update', $ffe))
        {
            return ErrorController::forbidden(route('ffes.index'), 'Unauthorised | Update FFE.');
        }

        //Find the locations that the user has been assigned to
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('ffe')->get();

        $manufacturers = Manufacturer::all();
        $suppliers = Supplier::all();
        $statuses = Status::all();
        // Return the Create View to the browser
        return view('FFE.edit', [
            "ffe" => $ffe,
            "locations" => $locations,
            "manufacturers" => $manufacturers,
            "suppliers" => $suppliers,
            "statuses" => $statuses,
        ]);
    }

    public function update(Request $request, FFE $ffe)
    {
        if(auth()->user()->cant('update', $ffe))
        {
            return ErrorController::forbidden(route('ffes.index'), 'Unauthorised | Update FFE.');
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

        if(isset($request->donated) && $request->donated == 1)
        {
            $donated = 1;
        } else
        {
            $donated = 0;
        }

        $ffe->fill(array_merge($request->only(
            'name', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'manufacturer_id', 'notes', 'photo_id', 'depreciation_id'
        ), ['donated' => $donated]))->save();
        session()->flash('success_message', $ffe->name . ' has been Updated successfully');
        if($request->category != '' && !empty(explode(',', $request->category)))
        {
            $ffe->category()->sync(explode(',', $request->category));
        }

        return to_route("ffes.index");
    }

    ////////////////////////////////////////////
    ////////////// Delete Functions ////////////
    ////////////////////////////////////////////

    public function destroy(FFE $ffe)
    {
        if(auth()->user()->cant('delete', $ffe))
        {
            return ErrorController::forbidden(route('ffes.index'), 'Unauthorised | Delete FFE.');

        }

        $name = $ffe->name;
        $ffe->delete();
        session()->flash('danger_message', $name . ' was sent to the Recycle Bin');

        return to_route('ffes.index');
    }

    public function restore($id)
    {
        $accessory = Accessory::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Restore Accessory.');
        }
        $accessory->restore();
        session()->flash('success_message', "#" . $accessory->name . ' has been restored.');

        return to_route("accessories.index");
    }

    public function forceDelete($id)
    {
        $accessory = Accessory::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('forceDelete', $accessory))
        {
            return ErrorController::forbidden(to_route('accessories.index'), 'Unauthorised to Delete Accessory.');

        }
        $name = $accessory->name;
        $accessory->forceDelete();
        session()->flash('danger_message', "Accessory - " . $name . ' was deleted permanently');

        return to_route('accessories.bin');
    }

}
