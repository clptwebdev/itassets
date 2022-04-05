<?php

namespace App\Policies;

use App\Models\FFE;
use App\Models\Machinery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MachineryPolicy {

    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Machinery')->first();
    }

    public function view(User $user, Machinery $machinery)
    {
        return $this->model->view && in_array($machinery->location_id, $user->locationsArray());
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

    public function generateShowPDF(User $user, Machinery $machinery)
    {
        return $this->model->fin_reports && in_array($machinery->location_id, $user->locationsArray());
    }

}
