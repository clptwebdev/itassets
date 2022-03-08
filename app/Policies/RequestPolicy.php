<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Request;
use App\Models\User;

class RequestPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Requests')->first();
    }

    public function handle(User $user)
    {
        return $this->model->view;
    }

}
