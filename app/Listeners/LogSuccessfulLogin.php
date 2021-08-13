<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'auth',
            'loggable_id'=> auth()->user()->id,
            'data'=>'User Logged in Successfully'
        ]);
    }
}
