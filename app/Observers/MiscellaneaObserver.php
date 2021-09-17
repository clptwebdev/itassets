<?php

namespace App\Observers;

use App\Models\Miscellanea;
use App\Models\Log;
use Carbon\Carbon;

class MiscellaneaObserver
{
    public function created(Miscellanea $miscellanea)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $miscellanea->id ?? 0 ,
            'data'=> $name.' created a new Miscellanea - '.$miscellanea->name
        ]);
    }

    public function updated(Miscellanea $miscellanea)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $miscellanea->id ?? 0,
            'data'=> $name.' updated Miscellanea - '.$miscellanea->name
        ]);
    }

    public function deleted(Miscellanea $miscellanea)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $miscellanea->id ?? 0,
            'data'=> $name.' deleted Miscellanea - '.$miscellanea->name
        ]);
    }

    public function restored(Miscellanea $miscellanea)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $miscellanea->id ?? 0,
            'data'=> $name.' restored Miscellanea - '.$miscellanea->name
        ]);
    }

    public function forceDeleted(Miscellanea $miscellanea)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'location',
            'loggable_id'=> $miscellanea->id ,
            'data'=> $name.' permanently removed Miscellanea - '.$miscellanea->name
        ]);
    }
}