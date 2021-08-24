<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Accessory;
use App\Models\User;

class AccessoryPolicy
{
    use HandlesAuthorization;

    
    public function viewAll(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function view(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($accessory->location_id, $locations)){
            return true;
        }else{
            return false;
        } 
    }

    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function update(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($accessory->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($accessory->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function restore(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($accessory->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function forceDelete(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($accessory->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function recycleBin(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function import(User $user,)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function export(User $user, Accessory $accessory)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function generatePDF(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function generateAccessoryPDF(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($accessory->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }
}
