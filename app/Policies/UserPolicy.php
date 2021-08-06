<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $admin, User $user)
    {
        $permission = 0;
        foreach($admin->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 || $admin->role_id == 1){
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
    public function edit(User $admin, User $user)
    {
        $permission = 0;
        foreach($admin->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 || $admin->role_id == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $admin, User $user)
    {
        $permission = 0;
        foreach($admin->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 || $admin->role_id == 1 && $admin->role_id <= $user->role_id){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
