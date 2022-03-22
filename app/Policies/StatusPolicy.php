<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Status;
use App\Models\User;

class StatusPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Status')->first();
    }

    public function viewAny(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, Status $status)
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

    public function delete(User $user, Status $status)
    {
        return $this->model->archive;
    }

    public function restore(User $user, Status $status)
    {
        return $this->model->archive;
    }

    public function forceDelete(User $user, Status $status)
    {
        return $this->model->delete;

    }

}
