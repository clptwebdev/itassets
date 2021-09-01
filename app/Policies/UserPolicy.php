<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAll(){
        return $user->role_id != 0 && $user->role_id <= 2;
    }

    public function view(User $admin, User $user)
    {
        $permission = 0;
        foreach($admin->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 && ($admin->role_id != 0 && $admin->role_id <= 2) || $admin->role_id == 1){
            return true;
        }else{
            return false;
        }
    }

    public function update(User $admin, User $user)
    {
        $permission = 0;
        foreach($admin->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 && ($admin->role_id != 0 && $admin->role_id <= 2) || $admin->role_id == 1){
            return true;
        }else{
            return false;
        }
    }

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

    public function restore(User $user, User $model)
    {
        //
    }

    public function forceDelete(User $user, User $model)
    {
    }

    public function permissions(User $user){
        return $user->role_id != 0 && $user->role_id <= 2;
    }
}
