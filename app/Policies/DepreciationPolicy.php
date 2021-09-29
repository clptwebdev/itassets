<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Depreciation;
use App\Models\User;

class DepreciationPolicy
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

    public function update(User $user, Depreciation $depreciation)
    {
        return in_array($user->role_id, $this->super);
    }

    public function delete(User $user, Depreciation $depreciation)
    {
        return in_array($user->role_id, $this->super);
    }

    public function restore(User $user, Depreciation $depreciation)
    {
        return in_array($user->role_id, $this->super);
    }

    public function forceDelete(User $user, Depreciation $depreciation)
    {
        return in_array($user->role_id, $this->super);
    }
}
