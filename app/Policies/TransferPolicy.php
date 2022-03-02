<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Asset;

class TransferPolicy {

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Transfer')->first();
    }

    public function approve(User $user)
    {
        return $this->model->create;
    }

    public function reject(User $user)
    {
        return $this->model->update;
    }

    public function viewAll(User $user)
    {
        return $this->model->view;
    }

}
