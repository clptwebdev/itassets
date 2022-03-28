<?php

namespace App\Observers;

use App\Models\location;
use App\Models\Log;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class LocationObserver {

    public function created(Location $location)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $location->id ?? 0,
            'data' => $name . ' created a new Location - ' . $location->name,
        ]);
        $role = Role::whereName('global_admin')->first();
        $globals = User::whereRoleId($role->id)->get();
        foreach($globals as $user)
        {
            $user->locations()->attach($location->id);

        }
    }

    public function updated(Location $location)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $location->id ?? 0,
            'data' => $name . ' updated Location - ' . $location->name,
        ]);
    }

    public function deleted(Location $location)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $location->id ?? 0,
            'data' => $name . ' deleted Location - ' . $location->name,
        ]);
    }

    public function restored(Location $location)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $location->id ?? 0,
            'data' => $name . ' restored Location - ' . $location->name,
        ]);
    }

    public function forceDeleted(Location $location)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $location->id,
            'data' => $name . ' permanently removed Location - ' . $location->name,
        ]);
    }

}
