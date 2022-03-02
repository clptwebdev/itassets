<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Accessory;
use App\Models\User;

class AccessoryPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Accessory')->first();
    }

    public function viewAll(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, Accessory $accessory)
    {
        return $this->model->view && in_array($accessory->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Accessory $accessory)
    {
        return $this->model->update && in_array($accessory->location_id, $user->locationsArray());

    }

    public function delete(User $user, Accessory $accessory)
    {
        return $this->model->archive && in_array($accessory->location_id, $user->locationsArray());
    }

    public function restore(User $user, Accessory $accessory)
    {
        return $this->model->archive && in_array($accessory->location_id, $user->locationsArray());
    }

    public function forceDelete(User $user, Accessory $accessory)
    {
        return $this->model->delete && in_array($accessory->location_id, $user->locationsArray());
    }

    public function recycleBin(User $user)
    {
        return $this->model->view;
    }

    public function import(User $user,)
    {
        return $this->model->create;
    }

    public function export(User $user, Accessory $accessory)
    {
        return $this->model->view;

    }

    public function generatePDF(User $user)
    {
        return $this->model->view;
    }

    public function generateAccessoryPDF(User $user, Accessory $accessory)
    {
        return $this->model->view && in_array($accessory->location_id, $user->locationsArray());
    }

    public function transfer(User $user, Accessory $accessory)
    {

        return $this->model->transfer && in_array($accessory->location_id, $user->locationsArray());
    }

    public function request()
    {

        return $this->model->request;
    }

    public function dispose(User $user, Accessory $accessory)
    {
        return $this->model->delete && in_array($accessory->location_id, $user->locationsArray());
    }

}
