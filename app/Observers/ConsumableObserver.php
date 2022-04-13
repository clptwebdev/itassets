<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\consumable;
use App\Models\Log;
use Carbon\Carbon;

class ConsumableObserver {

    public function created(Consumable $consumable)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'consumable',
            'loggable_id' => $consumable->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " created a new consumable - {$consumable->name}",
        ]);
    }

    public function updated(Consumable $consumable)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $consumable);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Consumable $consumable)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'consumable',
            'loggable_id' => $consumable->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " deleted consumable - {$consumable->name}",
        ]);
    }

    public function restored(Consumable $consumable)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'consumable',
            'loggable_id' => $consumable->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " restored consumable - {$consumable->name}",
        ]);
    }

    public function forceDeleted(Consumable $consumable)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'consumable',
            'loggable_id' => $consumable->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " permanently delted consumable - {$consumable->name}",
        ]);
    }

}
