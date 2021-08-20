<?php

namespace App\Observers;

use App\Models\Supplier;
use App\Models\Log;
use Carbon\Carbon;

class SupplierObserver
{
    /**
     * Handle the supplier "created" event.
     *
     * @param  \App\Models\supplier  $supplier
     * @return void
     */
    public function created(supplier $supplier)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'App\Models\Supplier',
            'loggable_id'=> $supplier->id,
            'data'=> auth()->user()->name.' created a new user with an Admin Role. Permissions were granted for [School Names]'
        ]);
    }

    /**
     * Handle the supplier "updated" event.
     *
     * @param  \App\Models\supplier  $supplier
     * @return void
     */
    public function updated(supplier $supplier)
    {
        //
    }

    /**
     * Handle the supplier "deleted" event.
     *
     * @param  \App\Models\supplier  $supplier
     * @return void
     */
    public function deleted(supplier $supplier)
    {
        //
    }

    /**
     * Handle the supplier "restored" event.
     *
     * @param  \App\Models\supplier  $supplier
     * @return void
     */
    public function restored(supplier $supplier)
    {
        //
    }

    /**
     * Handle the supplier "force deleted" event.
     *
     * @param  \App\Models\supplier  $supplier
     * @return void
     */
    public function forceDeleted(supplier $supplier)
    {
        //
    }
}
