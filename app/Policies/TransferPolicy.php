<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Asset;

class TransferPolicy
{
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
