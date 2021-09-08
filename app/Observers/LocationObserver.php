<?php

namespace App\Observers;

use App\Models\location;
use App\Models\Log;
use Carbon\Carbon;

class LocationObserver
{

    public function created(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ?? 0 ,
            'data'=> auth()->user()->name ?? "Unknown".' created a new Location - '.$location->name
        ]);
    }

    public function updated(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' updated Location - '.$location->name
        ]);
    }

    public function deleted(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' deleted Location - '.$location->name
        ]);
    }

    public function restored(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' restored Location - '.$location->name
        ]);
    }

    public function forceDeleted(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ,
            'data'=> auth()->user()->name ?? "Unknown".' permanently removed Location - '.$location->name
        ]);
    }
}
