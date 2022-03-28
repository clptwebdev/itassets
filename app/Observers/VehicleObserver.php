<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Vehicle;
use Carbon\Carbon;

class VehicleObserver {

    public function created(Vehicle $vehicle)
    {

        $location = 'It has been assigned to ' . $vehicle->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised User has added a new Vehicle: ' . $vehicle->name . '. ' . $location,
        ]);
    }

    public function updated(Vehicle $vehicle)
    {
        $location = 'It has been assigned to ' . $vehicle->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => auth()->user()->name . ' has added a updated Vehicle: ' . $vehicle->name . '. ' . $location,
        ]);
    }

    public function deleted(Vehicle $vehicle)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has placed the Vehicle: ' . $vehicle->name . ' into the recycling bin',
        ]);
    }

    public function restored(Vehicle $vehicle)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has restored the Vehicle: ' . $vehicle->name,
        ]);
    }

    public function forceDeleted(Vehicle $vehicle)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has permanently removed the Vehicle: ' . $vehicle->name,
        ]);
    }

}
