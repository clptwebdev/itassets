<?php

namespace App\Observers;

use App\Models\License;
use App\Models\Log;
use Carbon\Carbon;

class LicenseObserver {

    public function __construct()
    {
        $this->user = auth()->user()->name ?? 'An Unauthorized User';
    }

    public function created(License $license)
    {

        $location = 'It has been assigned to ' . $license->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'License',
            'loggable_id' => $license->id ?? 0,
            'data' => $this->user . ' has added a new License: ' . $license->name . '. ' . $location,
        ]);
    }

    public function updated(License $license)
    {
        $location = 'It has been assigned to ' . $license->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'License',
            'loggable_id' => $license->id ?? 0,
            'data' => $this->user . ' has added a updated License: ' . $license->name . '. ' . $location,
        ]);
    }

    public function deleted(License $license)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'License',
            'loggable_id' => $license->id ?? 0,
            'data' => $this->user . ' has placed the License: ' . $license->name . ' into the recycling bin',
        ]);
    }

    public function restored(License $license)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'License',
            'loggable_id' => $license->id ?? 0,
            'data' => $this->user . ' has restored the License: ' . $license->name,
        ]);
    }

    public function forceDeleted(License $license)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'License',
            'loggable_id' => $license->id ?? 0,
            'data' => $this->user . ' has permanently removed the License: ' . $license->name,
        ]);
    }

}
