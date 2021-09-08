<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
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

    public function update(User $user, Supplier $supplier)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function delete(User $user, Supplier $supplier)
    {
        return $user->role_id == 1;
    }

    public function restore(User $user, Supplier $supplier)
    {
        return $user->role_id == 1;
    }

    public function forceDelete(User $user, Supplier $supplier)
    {
        return $user->role_id == 1;
    }
}
