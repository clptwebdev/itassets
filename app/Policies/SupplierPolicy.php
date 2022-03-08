<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Supplier')->first();
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

    public function update(User $user, Supplier $supplier)
    {
        return $this->model->update;
    }

    public function delete(User $user, Supplier $supplier)
    {
        return $this->model->archive;
    }

    public function restore(User $user, Supplier $supplier)
    {
        return $this->model->archive;
    }

    public function forceDelete(User $user, Supplier $supplier)
    {
        return $this->model->delete;

    }

}
