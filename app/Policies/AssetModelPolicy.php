<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AssetModel;
use App\Models\User;

class AssetModelPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'AssetModel')->first();
    }

    public function viewAny(User $user)
    {
        return $this->model->view;


    }

    public function view(User $user)
    {
        return $this->model->view;

    }

    public function create(User $user)
    {
        return $this->model->create;


    }

    public function update(User $user)
    {
        return $this->model->update;

    }

    public function delete(User $user)
    {
        return $this->model->archive;

    }

    public function restore(User $user)
    {
        return $this->model->delete;

    }

    public function forceDelete(User $user)
    {
        return $this->model->delete;

    }

}
