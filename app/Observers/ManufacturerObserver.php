<?php

namespace App\Observers;

use App\Models\Manufacturer;
use App\Models\Log;
use Carbon\Carbon;

class ManufacturerObserver
{
    public function created(Manufacturer $manufacturer)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'manufacturer',
            'loggable_id'=> $manufacturer->id ?? 0,
            'data'=> $name.' created a new manufacturer - '.$manufacturer->name,
        ]);
    }

    public function updated(Manufacturer $manufacturer)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'manufacturer',
            'loggable_id'=> $manufacturer->id ?? 0,
            'data'=> $name.' updated manufacturer - '.$manufacturer->name,
        ]);
    }

    public function deleted(Manufacturer $manufacturer)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'manufacturer',
            'loggable_id'=> $manufacturer->id ?? 0,
            'data'=> $name.' deleted manufacturer - '.$manufacturer->name,
        ]);
    }

    public function restored(Manufacturer $manufacturer)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'manufacturer',
            'loggable_id'=> $manufacturer->id ?? 0,
            'data'=> $name.' restored manufacturer - '.$manufacturer->name,
        ]);
    }

    public function forceDeleted(Manufacturer $manufacturer)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'manufacturer',
            'loggable_id'=> $manufacturer->id ?? 0,
            'data'=> $name.' permanently deleted manufacturer - '.$manufacturer->name,
        ]);
    }
}
