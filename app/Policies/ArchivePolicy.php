<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Archive;
use App\Models\User;
use App\Models\Asset;

class ArchivePolicy
{
    use HandlesAuthorization;

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

    public function view(User $user, Archive $archive){
        return true;
    }

    public function delete(User $user, Archive $archive){
        return true;
    }

    public function generatePdf(User $user){
        return true;
    }
}
