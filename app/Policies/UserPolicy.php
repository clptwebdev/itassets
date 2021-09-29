<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    protected $super = [1];
    protected $admin = [1,2];
    protected $technician = [1,3];
    protected $manager = [1,2,3,4];
    protected $all = [1,2,3,4,5];

    public function viewAll(User $user){
        return in_array($user->role_id, $this->manager);
    }

    public function view(User $admin, User $user)
    {
        $permission = 0;
        foreach($admin->locations->pluck('id')->toArray() as $id => $key){
            if(in_array($key, $user->locations->pluck('id')->toArray())){
                $permission++;
            }
        }
        if($permission != 0 && in_array($user->role_id, $this->all)){
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

        if($permission != 0 && in_array($user->role_id, $this->admin) && $admin->role_id <= $user->role_id){
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
        if(in_array($admin->role_id, $this->super) || ($permission != 0 && in_array($admin->role_id, $this->admin) && $admin->role_id <= $user->role_id || $user->role_id == 0)){
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
        return in_array($user->role_id, $this->admin);
    }
}
