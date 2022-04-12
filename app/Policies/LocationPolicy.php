<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\location;
use App\Models\User;

class LocationPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Location')->first();
    }

    public function viewAll(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, Location $location)
    {

        return $this->model->view && in_array($location->id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Location $location)
    {
        return $this->model->update && in_array($location->id, $user->locationsArray());
    }

    public function delete(User $user, Location $location)
    {
        return $this->model->archive;
    }

    public function restore(User $user, Location $location)
    {

        return $this->model->archive;

    }

    public function forceDelete(User $user, Location $location)
    {
        return $this->model->delete;

    }

    public function businessReports(User $user, Location $location){
        return true;
        return $this->model->fin_reports && in_array($location->id, $user->locationsArray());
    }

}
