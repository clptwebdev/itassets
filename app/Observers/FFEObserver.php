<?php

namespace App\Observers;

use App\Models\FFE;
use App\Models\Log;
use Carbon\Carbon;

class FFEObserver {

    public function created(FFE $ffe)
    {

        $location = 'It has been assigned to ' . $ffe->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'FFE',
            'loggable_id' => $ffe->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised User has added a new FFE: ' . $ffe->name . '. ' . $location,
        ]);
    }

    public function updated(FFE $ffe)
    {
        $location = 'It has been assigned to ' . $ffe->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'FFE',
            'loggable_id' => $ffe->id ?? 0,
            'data' => auth()->user()->name . ' has added a updated FFE: ' . $ffe->name . '. ' . $location,
        ]);
    }

    public function deleted(FFE $ffe)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'FFE',
            'loggable_id' => $ffe->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has placed the FFE: ' . $ffe->name . ' into the recycling bin',
        ]);
    }

    public function restored(FFE $ffe)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'FFE',
            'loggable_id' => $ffe->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has restored the FFE: ' . $ffe->name,
        ]);
    }

    public function forceDeleted(FFE $ffe)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'FFE',
            'loggable_id' => $ffe->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has permanently removed the FFE: ' . $ffe->name,
        ]);
    }

}
