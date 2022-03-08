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

    public function viewAny(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, location $location)
    {
        return $this->model->view && in_array($location->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, location $location)
    {
        return $this->model->update && in_array($location->location_id, $user->locationsArray());
    }

    public function delete(User $user, location $location)
    {
        return $this->model->archive;
    }

    public function restore(User $user, location $location)
    {

        return $this->model->archive;

    }

    public function forceDelete(User $user, location $location)
    {
        return $this->model->delete;

    }

}
