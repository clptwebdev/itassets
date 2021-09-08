<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Manufacturer;
use App\Models\User;

class ManufacturerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function view(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function create(User $user)
    {
        return $user->role_id <= 3;
    }

    public function update(User $user, Manufacturer $manufacturer)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function delete(User $user, Manufacturer $manufacturer)
    {
        return $user->role_id == 1;
    }

    public function restore(User $user, Manufacturer $manufacturer)
    {
        return $user->role_id == 1;
    }

    public function forceDelete(User $user, Manufacturer $manufacturer)
    {
        return $user->role_id == 1;
    }
}
