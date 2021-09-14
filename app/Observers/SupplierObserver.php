<?php

namespace App\Observers;


use App\Models\Supplier;
use App\Models\Log;
use Carbon\Carbon;

class SupplierObserver
{
    public function created(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'supplier',
            'loggable_id'=> $supplier->id ?? 0,
            'data'=> $name.' created a new supplier - '.$supplier->name,
        ]);
    }

    public function updated(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'supplier',
            'loggable_id'=> $supplier->id ?? 0,
            'data'=> $name.' updated supplier - '.$supplier->name,
        ]);
    }

    public function deleted(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'supplier',
            'loggable_id'=> $supplier->id ?? 0,
            'data'=> $name.' deleted supplier - '.$supplier->name,
        ]);
    }

    public function restored(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'supplier',
            'loggable_id'=> $supplier->id ?? 0,
            'data'=> $name.' restored supplier - '.$supplier->name,
        ]);
    }

    public function forceDeleted(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'supplier',
            'loggable_id'=> $supplier->id ?? 0,
            'data'=> $name.' permanently deleted supplier - '.$supplier->name,
        ]);
    }
}
