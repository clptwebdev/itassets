<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id || $user->id == 1;
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id || $user->id == 1;
    }
}
