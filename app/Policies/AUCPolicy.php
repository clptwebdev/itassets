<?php

namespace App\Policies;

use App\Models\AUC;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AUCPolicy {

    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'AUC')->first();
    }

    public function view(User $user, AUC $auc)
    {
        return $this->model->view && in_array($auc->location_id, $user->locationsArray());
    }

    public function viewAll(User $user, AUC $auc)
    {
        return $this->model->view && in_array($auc->location_id, $user->locationsArray());
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

}
