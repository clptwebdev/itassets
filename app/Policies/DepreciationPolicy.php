<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Depreciation;
use App\Models\User;

class DepreciationPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Depreciation')->first();
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

    public function update(User $user, Depreciation $depreciation)
    {
        return $this->model->update;
    }

    public function delete(User $user, Depreciation $depreciation)
    {
        return $this->model->archive;
    }

    public function restore(User $user, Depreciation $depreciation)
    {
        return $this->model->archive;
    }

    public function forceDelete(User $user, Depreciation $depreciation)
    {
        return $this->model->delete;
    }

}
