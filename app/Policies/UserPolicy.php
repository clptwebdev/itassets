<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class UserPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'User')->first();
    }

    public function viewAll(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, User $accessedUser)
    {
        return $this->model->view || $user->id === $accessedUser->id;

    }

    public function create(User $user)
    {
        return $this->model->create;

    }

    public function update(User $user, User $accessedUser)
    {
        return $this->model->update || $user->id === $accessedUser->id;
    }

    public function delete(User $admin, User $user)
    {
        return $this->model->archive;
    }

    public function restore(User $user, User $model)
    {
        return $this->model->archive;

    }

    public function forceDelete(User $user, User $model)
    {
        return $this->model->delete;
    }

    public function permissions(User $user)
    {
        return $this->model->view;
    }

}
