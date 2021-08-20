<?php

namespace App\Observers;

use App\Models\Accessory;
use App\Models\Log;
use Carbon\Carbon;

class AccessoryObserver
{
    
    public function created(Accessory $accessory)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'accessory',
            'loggable_id'=> $accessory->id,
            'data'=> auth()->user()->name." created a new Accessory - {$accessory->name}"
        ]);
    }

    public function updated(Accessory $accessory)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'accessory',
            'loggable_id'=> $accessory->id,
            'data'=> auth()->user()->name." updated Accessory - {$accessory->name}"
        ]);
    }

    public function deleted(Accessory $accessory)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'accessory',
            'loggable_id'=> $accessory->id,
            'data'=> auth()->user()->name." deleted Accessory - {$accessory->name}"
        ]);
    }

    public function restored(Accessory $accessory)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'accessory',
            'loggable_id'=> $accessory->id,
            'data'=> auth()->user()->name." restored Accessory - {$accessory->name}"
        ]);
    }

    public function forceDeleted(Accessory $accessory)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'accessory',
            'loggable_id'=> $accessory->id,
            'data'=> auth()->user()->name." permanently deleted Accessory - {$accessory->name}"
        ]);
    }
}
