<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function view(User $user){
        return true;
    }

    public function viewAny(User $user){
        return true;
    }

    public function create(User $user){
        return true;
    }

    public function update(User $user){
        return true;
    }
}
