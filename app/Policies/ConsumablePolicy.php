<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Consumable;
use App\Models\User;

class ConsumablePolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Consumable')->first();
    }

    public function viewAll(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, Consumable $consumable)
    {
        return $this->model->view && in_array($consumable->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;

    }

    public function update(User $user, Consumable $consumable)
    {
        return $this->model->update && in_array($consumable->location_id, $user->locationsArray());

    }

    public function delete(User $user, Consumable $consumable)
    {
        return $this->model->archive && in_array($consumable->location_id, $user->locationsArray());

    }

    public function restore(User $user, Consumable $consumable)
    {
        return $this->model->archive && in_array($consumable->location_id, $user->locationsArray());

    }

    public function forceDelete(User $user, Consumable $consumable)
    {
        return $this->model->delete && in_array($consumable->location_id, $user->locationsArray());

    }

    public function recycleBin(User $user)
    {
        return $this->model->archive;

    }

    public function import(User $user,)
    {
        return $this->model->create;
    }

    public function export(User $user)
    {
        return $this->model->view;
    }

    public function generatePDF(User $user)
    {
        return $this->model->view;

    }

    public function generateConsumablePDF(User $user, Consumable $consumable)
    {
        return $this->model->view && in_array($consumable->location_id, $user->locationsArray());
    }

    public function transfer(User $user, Consumable $consumable)
    {
        return $this->model->transfer && in_array($consumable->location_id, $user->locationsArray());
    }

    public function request()
    {
        return $this->model->request;
    }

    public function dispose(User $user, Consumable $consumable)
    {
        return $this->model->delete && in_array($consumable->location_id, $user->locationsArray());

    }

}
