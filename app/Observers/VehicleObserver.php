<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Log;
use App\Models\Vehicle;
use Carbon\Carbon;

class VehicleObserver {

    public function __construct()
    {
        $this->user = auth()->user()->name ?? 'An Unauthorized User';
    }

    public function created(Vehicle $vehicle)
    {

        $location = 'It has been assigned to ' . $vehicle->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => $this->user . ' has added a new Vehicle: ' . $vehicle->name . '. ' . $location,
        ]);
    }

    public function updated(Vehicle $vehicle)
    {
        ////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $vehicle);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Vehicle $vehicle)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => $this->user . ' has placed the Vehicle: ' . $vehicle->name . ' into the recycling bin',
        ]);
    }

    public function restored(Vehicle $vehicle)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => $this->user . ' has restored the Vehicle: ' . $vehicle->name,
        ]);
    }

    public function forceDeleted(Vehicle $vehicle)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Vehicle',
            'loggable_id' => $vehicle->id ?? 0,
            'data' => $this->user . ' has permanently removed the Vehicle: ' . $vehicle->name,
        ]);
    }

}
