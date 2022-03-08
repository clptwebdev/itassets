<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy {

    use HandlesAuthorization;

    private $model;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Comment')->first();
    }

    public function comment(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Comment $comment)
    {
        return $this->model->update;
    }

    public function delete(User $user, Comment $comment)
    {
        return $this->model->delete;
    }

}
