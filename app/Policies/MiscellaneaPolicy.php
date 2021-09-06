<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Miscellanea;
use App\Models\User;

class MiscellaneaPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }


    public function view(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 4) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }


    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }


    public function update(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }


    public function delete(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }


    public function restore(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }


    public function forceDelete(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }
}
