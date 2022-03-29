<?php

namespace App\Policies;

use App\Models\Software;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoftwarePolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', '=', 'Software')->first();
    }

    public function viewAll(User $user)
    {
        return true;
    }

    public function view(User $user, Software $software)
    {
        return $this->model->view && in_array($software->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Software $software)
    {
        return $this->model->view && in_array($software->location_id, $user->locationsArray());
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

}
