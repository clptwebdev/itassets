<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\location;
use App\Models\User;

class LocationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function view(User $user, location $location)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($location->id, $locations) && ($user->role_id != 0 && $user->role_id <= 4) || $user->role_id == 1){
            return true;
        }else{
            return false;
        }
    }

    public function create(User $user)
    {
        return $user->role_id == 1;
    }

    public function update(User $user, location $location)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($location->id, $locations) && ($user->role_id != 0 && $user->role_id <= 4) || $user->role_id == 1){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, location $location)
    {
        return $user->role_id == 1;
    }

    public function restore(User $user, location $location)
    {
        return $user->role_id == 1;
    }

    public function forceDelete(User $user, location $location)
    {
        return $user->role_id == 1;
    }
}
