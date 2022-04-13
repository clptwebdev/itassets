<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;
use Schema;

class UserObserver {

    public function created(User $user)
    {
        $name = auth()->user()->name ?? 'System';
        $role = auth()->user()->role->name ?? 'N/A';
        $schools = "";
        foreach($user->locations as $location)
        {
            if($schools == "")
            {
                $schools .= $location->name;
            } else
            {
                $schools .= ", " . $location->name;
            }
        }
        if($schools == "")
        {
            $schools = "No Locations";
        }
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'user',
            'loggable_id' => $user->id,
            'data' => "{$name} created a new user with '{$role}' permissions. Access has been granted for {$schools}",
        ]);
    }

    public function updated(User $user)
    {
        $role = auth()->user()->role->name;
        $schools = "";
        foreach($user->locations as $location)
        {
            if($schools == "")
            {
                $schools .= $location->name;
            } else
            {
                $schools .= ", " . $location->name;
            }
        }
        if($schools == "")
        {
            $schools = "No Locations";
        }
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $user);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////

    }

    public function deleted(User $user)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'loggable_date' => Carbon::now(),
            'loggable_type' => 'user',
            'loggable_id' => $user->id,
            'data' => auth()->user()->name . ' has deleted ' . $user->name,
        ]);
    }

}
