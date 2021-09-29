<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    use HandlesAuthorization;

    protected $super = [1];
    protected $admin = [1,2];
    protected $technician = [1,3];
    protected $manager = [1,2,3,4];
    protected $all = [1,2,3,4,5];

    public function comment(User $user){
        return in_array($user->role_id, $this->manager);
    }

    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id || $user->id == 1;
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id || $user->id == 1;
    }
}
