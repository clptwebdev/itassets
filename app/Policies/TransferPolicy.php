<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Asset;

class TransferPolicy
{
    protected $super = [1];
    protected $admin = [1,2];
    protected $technician = [1,3];
    protected $manager = [1,2,3,4];
    protected $all = [1,2,3,4,5];

    public function approve(User $user)
    {
        return $user->role_id == 1;
    }

    public function reject(User $user)
    {
        return $user->role_id == 1;
    }

    public function viewAll(User $user){
        return $user->role_id != 0 && $user->role_id >= 5;
    }
}
