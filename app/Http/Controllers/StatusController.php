<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        if(auth()->user()->role_id == 1){
            $locations = \App\Models\Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('status.view', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        Status::create($request->only('name', 'deployable', 'icon', 'colour'));
        session()->flash('success_message', $request->name.' has been added to the statuses.');
        return redirect(route('status.index'));
    }

    public function show(Status $status)
    {
        if(auth()->user()->role_id == 1){
            $locations = \App\Models\Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('status.show', compact('status', 'locations'));
    }

    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $status->fill($request->only('name', 'deployable', 'icon', 'colour'))->save();
        session()->flash('success_message', $request->name.' has been updated successfully.');
        return redirect(route('status.index'));
    }

    public function destroy(Status $status)
    {
        $name = $status->name;
        $status->delete();
        session()->flash('danger_message', $name.' has been successfully deleted from the system');
        return redirect(route('status.index'));
    }
}
