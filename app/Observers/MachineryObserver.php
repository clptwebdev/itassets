<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Machinery;
use Carbon\Carbon;

class MachineryObserver {

    public function created(Machinery $machinery)
    {

        $location = 'It has been assigned to ' . $machinery->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Machinery',
            'loggable_id' => $machinery->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised User has added a new Machinery: ' . $machinery->name . '. ' . $location,
        ]);
    }

    public function updated(Machinery $machinery)
    {
        $location = 'It has been assigned to ' . $machinery->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Machinery',
            'loggable_id' => $machinery->id ?? 0,
            'data' => auth()->user()->name . ' has added a updated Machinery: ' . $machinery->name . '. ' . $location,
        ]);
    }

    public function deleted(Machinery $machinery)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Machinery',
            'loggable_id' => $machinery->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has placed the Machinery: ' . $machinery->name . ' into the recycling bin',
        ]);
    }

    public function restored(Machinery $machinery)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Machinery',
            'loggable_id' => $machinery->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has restored the Machinery: ' . $machinery->name,
        ]);
    }

    public function forceDeleted(Machinery $machinery)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Machinery',
            'loggable_id' => $machinery->id ?? 0,
            'data' => auth()->user()->name ?? 'A Un-Authorised' . ' has permanently removed the Machinery: ' . $machinery->name,
        ]);
    }

}
