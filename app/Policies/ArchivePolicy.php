<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Archive;
use App\Models\User;
use App\Models\Asset;

class ArchivePolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Archive')->first();
    }

    public function approve(User $user)
    {
        return $this->model->update;

    }

    public function reject(User $user)
    {
        return $this->model->delete;

    }

    public function viewAll(User $user)
    {
        return $this->model->view;


    }

    public function view(User $user, Archive $archive)
    {
        return $this->model->view;


    }

    public function delete(User $user, Archive $archive)
    {
        return $this->model->delete;


    }

    public function generatePdf(User $user)
    {
        return $this->model->view;


    }

}
