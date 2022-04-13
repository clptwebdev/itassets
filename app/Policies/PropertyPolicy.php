<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy {

    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Property')->first();
        $this->request = auth()->user()->role->permissions->where('model', ' = ', 'Requests')->first();
    }

    public function view(User $user, Property $property)
    {
        return $this->model->view && in_array($property->location_id, $user->locationsArray());
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

    public function archive(User $user){
        return $this->model->archive;
    }

    public function import(User $user){
        return $this->model->create;
    }

    public function generatePDF(User $user){
        return $this->model->fin_reports;
    }

    public function generateShowPDF(User $user, Property $property){
        return $this->model->fin_reports && in_array($property->location_id, $user->locationsArray());
    }

    public function bypass_transfer(User $user)
    {
        return $this->request->request;
    }

}
