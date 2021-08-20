<?php

namespace App\Observers;

use App\Models\Component;
use App\Models\Log;
use Carbon\Carbon;

class ComponentObserver
{
    
    public function created(Component $component)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'component',
            'loggable_id'=> $component->id,
            'data'=> auth()->user()->name." created a new Component - {$component->name}"
        ]);
    }

    public function updated(Component $component)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'component',
            'loggable_id'=> $component->id,
            'data'=> auth()->user()->name." updated Component - {$component->name}"
        ]);
    }

    public function deleted(Component $component)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'component',
            'loggable_id'=> $component->id,
            'data'=> auth()->user()->name." sent Component - {$component->name} to the Recycle Bin"
        ]);
    }

    public function restored(Component $component)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'component',
            'loggable_id'=> $component->id,
            'data'=> auth()->user()->name." restored Component - {$component->name}"
        ]);
    }

    public function forceDeleted(Component $component)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'component',
            'loggable_id'=> $component->id,
            'data'=> auth()->user()->name." has permanently deleted Component - {$component->name}"
        ]);
    }
}
