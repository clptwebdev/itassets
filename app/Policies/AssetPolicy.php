<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function view(User $user, Asset $asset)
    {
        //User needs to have the permissions of that location
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role_id === 1;
    }

    public function edit(User $user)
    {
        return $user->role_id === 1;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\asset  $asset
     * @return mixed
     */
    public function update(User $user, asset $asset)
    {
        return $user->role_id == 1;
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
        return $user->role_id == 1;
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
        //
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
        //
    }
}
