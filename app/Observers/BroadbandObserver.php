<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Broadband;
use App\Models\Log;
use Carbon\Carbon;

class BroadbandObserver {

    public function __construct()
    {
        $this->user = auth()->user()->name ?? 'An Unauthorized User';
    }

    public function created(Broadband $broadband)
    {

        $location = 'It has been assigned to ' . $broadband->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Broadband',
            'loggable_id' => $broadband->id ?? 0,
            'data' => $this->user . ' has added a new Broadband: ' . $broadband->name . '. ' . $location,
        ]);
    }

    public function updated(Broadband $broadband)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $broadband);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Broadband $broadband)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'Broadband',
            'loggable_id' => $broadband->id ?? 0,
            'data' => $this->user . ' has placed the Broadband: ' . $broadband->name . ' into the recycling bin',
        ]);
    }

    public function restored(Broadband $broadband)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Broadband',
            'loggable_id' => $broadband->id ?? 0,
            'data' => $this->user . ' has restored the Broadband: ' . $broadband->name,
        ]);
    }

    public function forceDeleted(Broadband $broadband)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'Broadband',
            'loggable_id' => $broadband->id ?? 0,
            'data' => $this->user . ' has permanently removed the Broadband: ' . $broadband->name,
        ]);
    }

}
