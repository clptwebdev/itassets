<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Property;
use App\Models\Log;
use Carbon\Carbon;

class PropertyObserver {

    public function created(Property $property)
    {

        $location = 'It has been assigned to ' . $property->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Property',
            'loggable_id' => $property->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised User has added a new Property: ' . $property->name . '. ' . $location,
        ]);
    }

    public function updated(Property $property)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $property);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Property $property)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Property',
            'loggable_id' => $property->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has placed the Property: ' . $property->name . ' into the recycling bin',
        ]);
    }

    public function restored(Property $property)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Property',
            'loggable_id' => $property->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has restored the Property: ' . $property->name,
        ]);
    }

    public function forceDeleted(Property $property)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Property',
            'loggable_id' => $property->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has permanently removed the Property: ' . $property->name,
        ]);
    }

}
