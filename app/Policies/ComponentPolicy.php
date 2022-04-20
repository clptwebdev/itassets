<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Component;
use App\Models\User;

class ComponentPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Component')->first();
    }

    public function viewAll(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, Component $component)
    {
        return $this->model->view && in_array($component->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;

    }

    public function update(User $user, Component $component)
    {
        return $this->model->update && in_array($component->location_id, $user->locationsArray());

    }

    public function delete(User $user, Component $component)
    {
        return $this->model->archive && in_array($component->location_id, $user->locationsArray());

    }

    public function restore(User $user, Component $component)
    {
        return $this->model->archive && in_array($component->location_id, $user->locationsArray());

    }

    public function forceDelete(User $user, Component $component)
    {
        return $this->model->delete && in_array($component->location_id, $user->locationsArray());

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

    public function generateComponentPDF(User $user, Component $component)
    {
        return $this->model->view && in_array($component->location_id, $user->locationsArray());
    }

    public function transfer(User $user, Component $component)
    {
        return $this->model->transfer && in_array($component->location_id, $user->locationsArray());
    }

    public function request()
    {
        return $this->model->request;
    }

    public function dispose(User $user, Component $component)
    {
        return $this->model->delete && in_array($component->location_id, $user->locationsArray());

    }

}
