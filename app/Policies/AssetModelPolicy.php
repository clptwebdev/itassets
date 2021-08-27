<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AssetModel;
use App\Models\User;

class AssetModelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function view(User $user, AssetModel $assetModel)
    {
        return $user->role_id != 0 && $user->role_id <= 4;
    }

    public function create(User $user)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function update(User $user, AssetModel $assetModel)
    {
        return $user->role_id != 0 && $user->role_id <= 3;
    }

    public function delete(User $user, AssetModel $assetModel)
    {
        return $user->role_id == 1;
    }

    public function restore(User $user, AssetModel $assetModel)
    {
        return $user->role_id == 1;
    }

    public function forceDelete(User $user, AssetModel $assetModel)
    {
        return $user->role_id == 1;
    }
}
