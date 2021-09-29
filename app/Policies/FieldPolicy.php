<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Field;
use App\Models\User;

class FieldPolicy
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

    public function update(User $user, Field $field)
    {
        return in_array($user->role_id, $this->super);
    }

    public function delete(User $user, Field $field)
    {
        return in_array($user->role_id, $this->super);
    }

    public function restore(User $user, Field $field)
    {
        return in_array($user->role_id, $this->super);
    }

    public function forceDelete(User $user, Field $field)
    {
        return in_array($user->role_id, $this->super);
    }
}
