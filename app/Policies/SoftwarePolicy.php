<?php

namespace App\Policies;

use App\Models\Software;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoftwarePolicy {

    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Software')->first();
        $this->request = auth()->user()->role->permissions->where('model', ' = ', 'Requests')->first();

    }

    public function view(User $user, Software $software)
    {
        return $this->model->view && in_array($software->location_id, $user->locationsArray());
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

    public function generateShowPDF(User $user, Software $software)
    {
        return $this->model->fin_reports && in_array($software->location_id, $user->locationsArray());
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
