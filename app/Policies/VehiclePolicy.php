<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy {

    use HandlesAuthorization;

    protected $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Vehicle')->first();
        $this->request = auth()->user()->role->permissions->where('model', ' = ', 'Requests')->first();
    }

    public function view(User $user, Vehicle $vehicle)
    {
        return $this->model->view && in_array($vehicle->location_id, $user->locationsArray());
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

    public function generatePDF(User $user)
    {
        return $this->model->fin_reports;
    }

    public function generateShowPDF(User $user, Vehicle $vehicle)
    {
        return $this->model->fin_reports && in_array($vehicle->location_id, $user->locationsArray());
    }

    public function bypass_transfer(User $user)
    {
        return $this->request->request;
    }

    public function request()
    {
        return $this->model->request;
    }

}
