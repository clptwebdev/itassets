<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('view', Status::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Statuses.');

        }
        $locations = auth()->user()->locations;

        return view('status.view', compact('locations'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('view', Status::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Statuses.');

        }
        $validated = $request->validate([
            'name' => 'required',
        ]);

        Status::create($request->only('name', 'deployable', 'icon', 'colour'));
        session()->flash('success_message', $request->name . ' has been added to the statuses.');

        return to_route('status.index');
    }

    public function show(Status $status)
    {
        if(auth()->user()->cant('view', Status::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Show Statuses.');

        }

        $locations = auth()->user()->locations;

        return view('status.show', compact('status', 'locations'));
    }

    public function update(Request $request, Status $status)
    {
        if(auth()->user()->cant('update', Status::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Update Statuses.');


        }
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $status->fill($request->only('name', 'deployable', 'icon', 'colour'))->save();
        session()->flash('success_message', $request->name . ' has been updated successfully.');

        return to_route('status.index');
    }

    public function destroy(Status $status)
    {
        if(auth()->user()->cant('delete', Status::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Delete Statuses.');

        }
        $name = $status->name;
        $status->delete();
        session()->flash('danger_message', $name . ' has been successfully deleted from the system');

        return to_route('status.index');
    }

}
