<?php

namespace App\Observers;

use App\Models\consumable;
use App\Models\Log;
use Carbon\Carbon;

class ConsumableObserver
{
    
    public function created(Consumable $consumable)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'consumable',
            'loggable_id'=> $consumable->id,
            'data'=> auth()->user()->name." created a new consumable - {$consumable->name}"
        ]);
    }

    public function updated(Consumable $consumable)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'consumable',
            'loggable_id'=> $consumable->id,
            'data'=> auth()->user()->name." edited consumable - {$consumable->name}"
        ]);
    }

    public function deleted(Consumable $consumable)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'consumable',
            'loggable_id'=> $consumable->id,
            'data'=> auth()->user()->name." deleted consumable - {$consumable->name}"
        ]);
    }

    public function restored(Consumable $consumable)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'consumable',
            'loggable_id'=> $consumable->id,
            'data'=> auth()->user()->name." restored consumable - {$consumable->name}"
        ]);
    }

    public function forceDeleted(Consumable $consumable)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'consumable',
            'loggable_id'=> $consumable->id,
            'data'=> auth()->user()->name." permanently delted consumable - {$consumable->name}"
        ]);
    }
}
