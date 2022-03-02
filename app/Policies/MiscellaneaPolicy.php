<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Miscellanea;
use App\Models\User;

class MiscellaneaPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Miscellanea')->first();
    }

    public function viewAny(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user, Miscellanea $miscellanea)
    {
        return $this->model->view && in_array($miscellanea->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Miscellanea $miscellanea)
    {
        return $this->model->update && in_array($miscellanea->location_id, $user->locationsArray());

    }

    public function delete(User $user, Miscellanea $miscellanea)
    {
        return $this->model->archive && in_array($miscellanea->location_id, $user->locationsArray());
    }

    public function forceDelete(User $user, Miscellanea $miscellanea)
    {
        return $this->model->delete && in_array($miscellanea->location_id, $user->locationsArray());

    }

    public function recycleBin(User $user)
    {
        return $this->model->view;
    }

    public function import(User $user,)
    {
        return $this->model->create;
    }

    public function export(User $user, Miscellanea $miscellanea)
    {
        return $this->model->view && in_array($miscellanea->location_id, $user->locationsArray());
    }

    public function generatePDF(User $user)
    {
        return $this->model->view;
    }

    public function generateMiscellaneaPDF(User $user, Miscellanea $miscellanea)
    {
        return $this->model->view && in_array($miscellanea->location_id, $user->locationsArray());
    }

    public function transfer(User $user, Miscellanea $miscellanea)
    {
        return $this->model->transfer && in_array($miscellanea->location_id, $user->locationsArray());
    }

    public function request()
    {

        return $this->model->request;
    }

    public function dispose(User $user, Miscellanea $miscellanea)
    {
        return $this->model->delete && in_array($miscellanea->location_id, $user->locationsArray());
    }

}
