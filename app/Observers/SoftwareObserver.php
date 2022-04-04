<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Software;
use Carbon\Carbon;

class SoftwareObserver {

    public function __construct()
    {
        $this->user = auth()->user()->name ?? 'An Unauthorized User';
    }

    public function created(Software $software)
    {

        $location = 'It has been assigned to ' . $software->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Software',
            'loggable_id' => $software->id ?? 0,
            'data' => $this->user . ' has added a new Software: ' . $software->name . '. ' . $location,
        ]);
    }

    public function updated(Software $software)
    {
        $location = 'It has been assigned to ' . $software->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Software',
            'loggable_id' => $software->id ?? 0,
            'data' => $this->user . ' has added a updated Software: ' . $software->name . '. ' . $location,
        ]);
    }

    public function deleted(Software $software)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Software',
            'loggable_id' => $software->id ?? 0,
            'data' => $this->user . ' has placed the Software: ' . $software->name . ' into the recycling bin',
        ]);
    }

    public function restored(Software $software)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Software',
            'loggable_id' => $software->id ?? 0,
            'data' => $this->user . ' has restored the Software: ' . $software->name,
        ]);
    }

    public function forceDeleted(Software $software)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Software',
            'loggable_id' => $software->id ?? 0,
            'data' => $this->user . ' has permanently removed the Software: ' . $software->name,
        ]);
    }

}
