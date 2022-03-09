<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Report')->first();
    }

    public function view(User $user)
    {
        return $this->model->view;
    }

    public function viewAll(User $user)
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

    public function recycleBin(User $user)
    {
        return $this->model->archive;
    }

    public function delete(User $user)
    {
        return $this->model->delete;
    }

}
