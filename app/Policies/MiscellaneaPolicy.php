<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Miscellanea;
use App\Models\User;

class MiscellaneaPolicy
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

    public function view(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function update(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->manager) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->manager) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function restore(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->manager) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function forceDelete(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->super) && in_array($miscellanea->location_id, $locations)){
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

    public function export(User $user, Miscellanea $miscellanea)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generatePDF(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generateMiscellaneaPDF(User $user, Miscellanea $miscellanea)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($miscellanea->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function transfer(User $user, Miscellanea $miscellanea){
        $locations = $user->locations->pluck('id')->toArray();
        return in_array($user->role_id, $this->technician) && in_array($miscellanea->location_id, $locations);
    }

    public function dispose(User $user, Miscellanea $miscellanea){
        $locations = $user->locations->pluck('id')->toArray();
        return in_array($user->role_id, $this->technician) && in_array($miscellanea->location_id, $locations);
    }
}
