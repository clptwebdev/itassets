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
        /* $this->middleware('auth');
        $this->middleware('auth')->only(['functionName1', 'functionName2']);
        $this->middleware('auth')->except(['functionName1', 'functionName2']); */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        if(auth()->user()->role_id == 1){
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        
        return view ('users.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
        $permission = 0;
        foreach(auth()->user()->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 || auth()->user()->role_id == 1){
            $location = Location::find($user->location_id);
            return view('users.show', compact('user', 'location'));
        }else{
            return redirect('/permissions');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        if(auth()->user()->role_id == 1){
            $locations = Location::all();
            return view('users.edit', compact('user', 'locations'));
        }else{
            $permission = 0;
            foreach(auth()->user()->locations->pluck('id')->toArray() as $id => $key){
                if(in_array($key, $user->locations->pluck('id')->toArray())){
                    $permission++;
                }
            }
            if($permission != 0){
                $locations = auth()->user()->locations;
                return view('users.edit', compact('user', 'locations'));
            }else{
                return redirect('/permissions');
            }
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(auth()->user()->role_id == 1 || auth()->user()->role_id <= $user->role_id){
            $name=$user->name;
            $user->delete();
            session()->flash('danger_message', $name . ' was deleted from the system');
            return redirect(route('users.index'));
        }else{
            return redirect('/permissions');
        }
    }

    public function export(User $user)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new UserExport, 'users.csv');

    }

    public function permissions(Request $request){
        if($request->ajax()){
            $ids = $request->ids;
            return view('users.permissions', compact('ids'));
        }else{
            return 'Not Ajax';
        }
    }
}
