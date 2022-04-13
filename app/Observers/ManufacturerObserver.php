<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\Manufacturer;
use App\Models\Log;
use Carbon\Carbon;

class ManufacturerObserver {

    public function __construct()
    {
        $this->user = $this->user . 'An Unauthorized User';
    }

    public function created(Manufacturer $manufacturer)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'manufacturer',
            'loggable_id' => $manufacturer->id ?? 0,
            'data' => $this->user . ' created a new manufacturer - ' . $manufacturer->name,
        ]);
    }

    public function updated(Manufacturer $manufacturer)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $manufacturer);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(Manufacturer $manufacturer)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'manufacturer',
            'loggable_id' => $manufacturer->id ?? 0,
            'data' => $this->user . ' deleted manufacturer - ' . $manufacturer->name,
        ]);
    }

    public function restored(Manufacturer $manufacturer)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'manufacturer',
            'loggable_id' => $manufacturer->id ?? 0,
            'data' => $this->user . ' restored manufacturer - ' . $manufacturer->name,
        ]);
    }

    public function forceDeleted(Manufacturer $manufacturer)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'manufacturer',
            'loggable_id' => $manufacturer->id ?? 0,
            'data' => $this->user . ' permanently deleted manufacturer - ' . $manufacturer->name,
        ]);
    }

}
