<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::create([
            'user_id'=>auth()->user()->id, 
            'log_date'=> Carbon::now(),
            'log_type'=> 'user',
            'log_id'=> $user->id, 
            'data'=> auth()->user()->name.' created a new user with an Admin Role. Permissions were granted for [School Names]'
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        switch($user->role_id){
            case 0:
                $role = 'No Access';
                break;
            case 1:
                $role = 'Super Admin';
                break;
            case 2:
                $role = 'Administrator';
                break;
            case 3:
                $role = 'User Manager';
                break;
            case 4:
                $role = 'User';
                break;
        }
        Log::create([
            'user_id'=>auth()->user()->id, 
            'log_date'=> Carbon::now(),
            'log_type'=> 'user',
            'log_id'=> $user->id, 
            'data'=> auth()->user()->name." updated user: {$user->name}. The Role of {$user->name} has been set to {$role}"
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        Log::create([
            'user_id'=>auth()->user()->id, 
            'log_date'=> Carbon::now(),
            'log_type'=> 'user',
            'log_id'=> $user->id, 
            'data'=> auth()->user()->name.' has deleted '.$user->name,
        ]);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        
    }
}
