<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\location;
use App\Models\User;

class LocationPolicy
{
    use HandlesAuthorization;

    protected $super = [1];
    protected $admin = [1,2];
    protected $technician = [1,3];
    protected $manager = [1,2,3,4];
    protected $all = [1,2,3,4,5];

    public function viewAny(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function view(User $user, location $location)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($location->id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function create(User $user)
    {
        return in_array($user->role_id, $this->super);
    }

    public function update(User $user, location $location)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($location->id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, location $location)
    {
        return in_array($user->role_id, $this->super);
    }

    public function restore(User $user, location $location)
    {
        return in_array($user->role_id, $this->super);
    }

    public function forceDelete(User $user, location $location)
    {
        return in_array($user->role_id, $this->super);
    }
}
