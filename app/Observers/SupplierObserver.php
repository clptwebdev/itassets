<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Supplier;
use App\Models\Log;
use Carbon\Carbon;

class SupplierObserver {

    public function created(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'supplier',
            'loggable_id' => $supplier->id ?? 0,
            'data' => $name . ' created a new supplier - ' . $supplier->name,
        ]);
    }

    public function updated(supplier $supplier)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $supplier);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'supplier',
            'loggable_id' => $supplier->id ?? 0,
            'data' => $name . ' deleted supplier - ' . $supplier->name,
        ]);
    }

    public function restored(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'supplier',
            'loggable_id' => $supplier->id ?? 0,
            'data' => $name . ' restored supplier - ' . $supplier->name,
        ]);
    }

    public function forceDeleted(supplier $supplier)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'supplier',
            'loggable_id' => $supplier->id ?? 0,
            'data' => $name . ' permanently deleted supplier - ' . $supplier->name,
        ]);
    }

}
