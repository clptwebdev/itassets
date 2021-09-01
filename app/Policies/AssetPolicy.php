<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\Asset;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class AssetPolicy
{
    use HandlesAuthorization;


    public function viewAll(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function view(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function update(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function recycleBin(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function generatePDF(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function generateAssetPDF(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function restore(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function forceDelete(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || ($user->role_id != 0 && $user->role_id <= 3) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }
}
