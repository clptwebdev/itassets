<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Request;
use App\Models\User;

class RequestPolicy
{
    use HandlesAuthorization;

    public function handle(User $user){
        return $user->role_id == 1;
    }
}
