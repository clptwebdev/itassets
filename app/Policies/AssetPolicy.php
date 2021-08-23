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


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function view(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 4 && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role_id <= 3;
    }

    public function edit(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 3 && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function update(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 3 && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function delete(User $user, asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 3 && in_array($asset->location_id, $locations)){

            return true;
        }else{
            return false;
        }
    }

    public function recycleBin(User $user){
        return $user->role_id <= 4;
    }

    public function generatePDF(User $user){
        return $user->role_id <= 3;
    }

    public function generateAssetPDF(User $user, asset $asset){
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 3 && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function restore(User $user, asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 3 && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function forceDelete(User $user, asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if($user->role_id == 1 || $user->role_id <= 3 && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }
}
