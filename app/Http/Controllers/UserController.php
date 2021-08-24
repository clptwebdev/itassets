<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Location;

class UserController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        if(auth()->user()->role_id == 1){
            $users = User::all();
        }else{
            $users = User::whereHas('locations', function ($query) {
                $locs = [];
                foreach(auth()->user()->locations as $loc){
                    $locs[] = $loc->id;
                }
                $query->whereIn('locations.id', $locs);
            })->get();
        }
       
        return view ('users.view', compact('users'));
    }

    public function create()
    {
        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        
        return view ('users.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email'=>'required|unique:users|email:rfc,dns,spoof,filter',
        ]);

        $user = User::create(array_merge($request->only('name', 'email', 'location_id', 'role_id'), ['password' => '123']));
        $array = explode(',', $request->permission_ids);
        $user->locations()->attach($array);
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('users.index'));
    }

    public function show(User $user)
    {
        if (auth()->user()->cant('view', $user)) {
            return redirect(route('errors.forbidden', ['user', $user->id, 'view']));
        }
        
        $location = Location::find($user->location_id);
        return view('users.show', compact('user', 'location'));
    }

    public function edit(User $user)
    {
        if (auth()->user()->cant('edit', $user)) {
            return redirect(route('errors.forbidden', ['user', $user->id, 'edit']));
        }else{
            if(auth()->user()->role_id == 1){
                $locations = Location::all();
            }else{
                $locations = auth()->user()->locations;
            }
            return view('users.edit', compact('user', 'locations'));
        }
        
    }

    public function update(Request $request, User $user)
    {
        $validated=$request->validate([
            'name'=>'required|max:255',
            'email'=>['required', \Illuminate\Validation\Rule::unique('users')->ignore($user->id), 'email:rfc,dns,spoof,filter'],
        ]);

        $user->fill($request->only('name', 'email', 'location_id', 'role_id'))->save();
        $array = explode(',', $request->permission_ids);
        $user->locations()->sync($array);
        session()->flash('success_message', $request->name.' has been updated successfully');
        return redirect(route('users.index'));
    }

    public function destroy(User $user)
    {
        if (auth()->user()->cant('delete', $user)) {
            return redirect(route('errors.forbidden', ['user', $user->id, 'edit']));
        }else{
                $name=$user->name;
                $user->delete();
                session()->flash('danger_message', $name . ' was deleted from the system');
                return redirect(route('users.index'));
        }
    }

    public function export(User $user)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new UserExport, 'users.csv');

    }

    public function permissions(Request $request)
    {
        if($request->ajax()){
            $ids = $request->ids;
            return view('users.permissions', compact('ids'));
        }else{
            return 'Not Ajax';
        }
    }

}
