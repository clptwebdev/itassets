<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Manufacturer;
use App\Models\User;

class ManufacturerPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Manufacturer')->first();
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

    public function update(User $user, Manufacturer $manufacturer)
    {
        return $this->model->update;
    }

    public function delete(User $user, Manufacturer $manufacturer)
    {
        return $this->model->archive;

    }

    public function restore(User $user, Manufacturer $manufacturer)
    {
        return $this->model->archive;
    }

    public function forceDelete(User $user, Manufacturer $manufacturer)
    {
        return $this->model->delete;
    }

}
