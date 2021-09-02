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
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ,
            'data'=> auth()->user()->name.' created a new Location - '.$location->name
        ]);
    }

    public function updated(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ,
            'data'=> auth()->user()->name.' updated Location - '.$location->name
        ]);
    }

    public function deleted(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ,
            'data'=> auth()->user()->name.' deleted Location - '.$location->name
        ]);
    }

    public function restored(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ,
            'data'=> auth()->user()->name.' restored Location - '.$location->name
        ]);
    }

    public function forceDeleted(location $location)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $location->id ,
            'data'=> auth()->user()->name.' permanently removed Location - '.$location->name
        ]);
    }
}
