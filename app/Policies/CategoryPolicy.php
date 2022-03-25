<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Category;
use App\Models\User;

class CategoryPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Category')->first();
    }

    public function viewAny(User $user)
    {
        return $this->model->view;
    }

    public function view(User $user)
    {
        return $this->model->view;
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Category $category)
    {
        return $this->model->update;
    }

    public function delete(User $user, Category $category)
    {
        return $this->model->archive;
    }

    public function restore(User $user, Category $category)
    {
        return $this->model->archive;

    }

    public function forceDelete(User $user, Category $category)
    {
        return $this->model->delete;

    }

}
