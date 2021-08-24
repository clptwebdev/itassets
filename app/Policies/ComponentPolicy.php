<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Component;
use App\Models\User;

class ComponentPolicy
{
    use HandlesAuthorization;

    public function viewAll(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function view(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray( );
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function update(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function restore(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function forceDelete(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function recycleBin(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function import(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function export(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function generatePDF(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function generateComponentPDF(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    
}
