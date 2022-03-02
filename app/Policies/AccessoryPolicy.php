<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Accessory;
use App\Models\User;

class AccessoryPolicy {

    use HandlesAuthorization;

    protected $super = [1];
    protected $admin = [1, 2];
    protected $technician = [1, 3];
    protected $manager = [1, 2, 3, 4];
    protected $all = [1, 2, 3, 4, 5];

    public function viewAll(User $user)
    {
//        return $user->role->permissions->where('model', ' = ', 'Accessory')->first()->view;
        return in_array($user->role_id, $this->all);
    }

    public function view(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
//        if($user->role->permissions->where('model', ' = ', 'Accessory')->first()->view && in_array($accessory->location_id, $locations))
//        {
//            return true;
//        } else
//        {
//            return false;
//        }

        if(in_array($user->role_id, $this->super) || (in_array($user->role_id, $this->all) && in_array($accessory->location_id, $locations)))
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function update(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->super) || (in_array($user->role_id, $this->manager) && in_array($accessory->location_id, $locations)))
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function delete(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->super) || (in_array($user->role_id, $this->manager) && in_array($accessory->location_id, $locations)))
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function restore(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->super) || (in_array($user->role_id, $this->manager) && in_array($accessory->location_id, $locations)))
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function forceDelete(User $user, Accessory $accessory)
    {
        if(in_array($user->role_id, $this->super))
        {
            return true;
        } else
        {
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

    public function export(User $user, Accessory $accessory)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generatePDF(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generateAccessoryPDF(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($accessory->location_id, $locations))
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function transfer(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();

        return in_array($user->role_id, $this->technician) && in_array($accessory->location_id, $locations);
    }

    public function dispose(User $user, Accessory $accessory)
    {
        $locations = $user->locations->pluck('id')->toArray();

        return in_array($user->role_id, $this->technician) && in_array($accessory->location_id, $locations);
    }

}
