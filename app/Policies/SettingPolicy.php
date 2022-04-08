<?php

namespace App\Policies;

use App\Models\Software;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy {

    use HandlesAuthorization;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Setting')->first();
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

}
