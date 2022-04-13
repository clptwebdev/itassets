<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Miscellanea;
use App\Models\Log;
use Carbon\Carbon;

class MiscellaneaObserver {

    public function __construct()
    {
        $this->user = $this->user . 'An Unauthorized User';
    }

    public function created(Miscellanea $miscellanea)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $miscellanea->id ?? 0,
            'data' => $this->user . ' created a new Miscellanea - ' . $miscellanea->name,
        ]);
    }

    public function updated(Miscellanea $miscellanea)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $miscellanea);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Miscellanea $miscellanea)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $miscellanea->id ?? 0,
            'data' => $this->user . ' deleted Miscellanea - ' . $miscellanea->name,
        ]);
    }

    public function restored(Miscellanea $miscellanea)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $miscellanea->id ?? 0,
            'data' => $this->user . ' restored Miscellanea - ' . $miscellanea->name,
        ]);
    }

    public function forceDeleted(Miscellanea $miscellanea)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'log_date' => Carbon::now(),
            'loggable_type' => 'location',
            'loggable_id' => $miscellanea->id,
            'data' => $this->user . ' permanently removed Miscellanea - ' . $miscellanea->name,
        ]);
    }

}
