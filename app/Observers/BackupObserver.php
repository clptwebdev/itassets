<?php

namespace App\Observers;

use App\Models\Backup;

use App\Models\Log;
use Carbon\Carbon;

class BackupObserver {

    public function __construct()
    {
        $this->user = $this->user . 'An Unauthorized User';
    }

    public function created(Backup $backup)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'backup',
            'loggable_id' => $backup->id ?? 0,
            'data' => $this->user . ' created a new backup - ' . $backup->name,
        ]);
    }

    public function updated(Backup $backup)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'backup',
            'loggable_id' => $backup->id ?? 0,
            'data' => $this->user . ' updated backup - ' . $backup->name,
        ]);
    }

    public function deleted(Backup $backup)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'backup',
            'loggable_id' => $backup->id ?? 0,
            'data' => $this->user . ' deleted backup - ' . $backup->name,
        ]);
    }

    public function restored(Backup $backup)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'backup',
            'loggable_id' => $backup->id ?? 0,
            'data' => $this->user . ' restored backup - ' . $backup->name,
        ]);
    }

    public function forceDeleted(Backup $backup)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'log_date' => Carbon::now(),
            'loggable_type' => 'backup',
            'loggable_id' => $backup->id,
            'data' => $this->user . ' permanently removed backup - ' . $backup->name,
        ]);
    }

}
