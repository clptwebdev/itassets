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
        $name = auth()->user()->id ?? 'System';
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
        $schools = "";
        if($user->role_id == 1){
            $schools = "All Locations";
        }else{
            foreach($user->locations as $location){
                if($schools == ""){
                    $schools .= $location->name;
                }else{
                    $schools .= ", ".$location->name;
                }
            }
        }
        if($schools == ""){ $schools = "No Locations";}
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'user',
            'loggable_id'=> $user->id,
            'data'=> "{$name} created a new user with '{$role}' permissions. Access has been granted for {$schools}"
        ]);
    }

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
        $schools = "";
        if($user->role_id == 1){
            $schools = "All Locations";
        }else{
            foreach($user->locations as $location){
                if($schools == ""){
                    $schools .= $location->name;
                }else{
                    $schools .= ", ".$location->name;
                }
            }
        }
        if($schools == ""){ $schools = "No Locations";}
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'user',
            'loggable_id'=> $user->id,
            'data'=> auth()->user()->name." updated user: {$user->name}. The Role of {$user->name} has been set to {$role}. Access Granted for {$schools}"
        ]);
    }

    public function deleted(User $user)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'user',
            'loggable_id'=> $user->id,
            'data'=> auth()->user()->name.' has deleted '.$user->name,
        ]);
    }
}
