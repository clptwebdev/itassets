<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Manufacturer;
use App\Models\User;

class ManufacturerPolicy
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

    public function view(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function create(User $user)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function update(User $user, Manufacturer $manufacturer)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function delete(User $user, Manufacturer $manufacturer)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function restore(User $user, Manufacturer $manufacturer)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function forceDelete(User $user, Manufacturer $manufacturer)
    {
        return in_array($user->role_id, $this->super);
    }
}
