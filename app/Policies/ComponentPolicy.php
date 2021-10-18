<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Component;
use App\Models\User;

class ComponentPolicy
{
    use HandlesAuthorization;

    protected $super = [1];
    protected $admin = [1,2];
    protected $technician = [1,3];
    protected $manager = [1,2,3,4];
    protected $all = [1,2,3,4,5];

    
    public function viewAll(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function view(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($component->location_id, $locations)){
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
        if(in_array($user->role_id, $this->manager) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->manager) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function restore(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->manager) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function forceDelete(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->super) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function recycleBin(User $user)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function import(User $user,)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function export(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generatePDF(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generateComponentPDF(User $user, Component $component)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($component->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function transfer(User $user, Component $component){
        $locations = $user->locations->pluck('id')->toArray();
        return in_array($user->role_id, $this->technician) && in_array($component->location_id, $locations);
    }

    public function dispose(User $user, Component $component){
        $locations = $user->locations->pluck('id')->toArray();
        return in_array($user->role_id, $this->technician) && in_array($component->location_id, $locations);
    }

    
}
