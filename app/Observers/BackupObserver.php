<?php

namespace App\Observers;

use App\Models\Backup;

use App\Models\Log;
use Carbon\Carbon;

class BackupObserver
{
    public function created(Backup $backup)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'backup',
            'loggable_id'=> $backup->id ?? 0 ,
            'data'=> $name.' created a new backup - '.$backup->name
        ]);
    }

    public function updated(Backup $backup)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'backup',
            'loggable_id'=> $backup->id ?? 0,
            'data'=> $name.' updated backup - '.$backup->name
        ]);
    }

    public function deleted(Backup $backup)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'backup',
            'loggable_id'=> $backup->id ?? 0,
            'data'=> $name.' deleted backup - '.$backup->name
        ]);
    }

    public function restored(Backup $backup)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'backup',
            'loggable_id'=> $backup->id ?? 0,
            'data'=> $name.' restored backup - '.$backup->name
        ]);
    }

    public function forceDeleted(Backup $backup)
    {
        $name = auth()->user()->name ?? "Unknown";
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'backup',
            'loggable_id'=> $backup->id ,
            'data'=> $name.' permanently removed backup - '.$backup->name
        ]);
    }
}
