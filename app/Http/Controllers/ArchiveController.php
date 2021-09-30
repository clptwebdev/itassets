<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Location;

class ArchiveController extends Controller
{
    
    public function index(){
        if(auth()->user()->role_id == 1){
            $archives = Archive::all();
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
            $location_ids = $locations->pluck('id');
            $archives = Archive::whereIn('location_id', $location_ids)->get();
        }

        $title = "Archived/Disposed";
        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function assets(){
        if (auth()->user()->cant('viewAll', Asset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $archives = Archive::whereModelType('asset')->get();
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
            $location_ids = $locations->pluck('id');
            $archives = Archive::whereIn('location_id', $location_ids)->whereModelType('asset')->get();
        }

        $title = "Archived Assets";

        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function accessories(){
        if (auth()->user()->cant('viewAll', Asset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $archives = Archive::whereModelType('accessory')->get();
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
            $location_ids = $locations->pluck('id');
            $archives = Archive::whereIn('location_id', $location_ids)->whereModelType('accessory')->get();
        }

        $title = "Archived Accessories";

        return view('archives.view', compact('archives', 'locations', 'title')); 
    }

}
