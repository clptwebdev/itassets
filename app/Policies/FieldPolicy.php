<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Field;
use App\Models\User;

class FieldPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Field')->first();
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

    public function update(User $user, Field $field)
    {
        return $this->model->update;

    }

    public function delete(User $user, Field $field)
    {
        return $this->model->archive;

    }

    public function restore(User $user, Field $field)
    {
        return $this->model->archive;

    }

    public function forceDelete(User $user, Field $field)
    {
        return $this->model->delete;

    }

}
