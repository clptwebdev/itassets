<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Accessory;
use App\Models\Log;
use Carbon\Carbon;

class AccessoryObserver {

    public function __construct()
    {
        $this->user = $this->user . 'An Unauthorized User';
    }

    public function created(Accessory $accessory)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'accessory',
            'loggable_id' => $accessory->id ?? 0,
            'data' => $this->user . "Unknown" . " created a new Accessory - {$accessory->name}",
        ]);
    }

    public function updated(Accessory $accessory)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $accessory);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Accessory $accessory)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'accessory',
            'loggable_id' => $accessory->id ?? 0,
            'data' => $this->user . "Unknown" . " deleted Accessory - {$accessory->name}",
        ]);
    }

    public function restored(Accessory $accessory)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'accessory',
            'loggable_id' => $accessory->id ?? 0,
            'data' => $this->user . "Unknown" . " restored Accessory - {$accessory->name}",
        ]);
    }

    public function forceDeleted(Accessory $accessory)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'accessory',
            'loggable_id' => $accessory->id ?? 0,
            'data' => $this->user . "Unknown" . " permanently deleted Accessory - {$accessory->name}",
        ]);
    }

}
