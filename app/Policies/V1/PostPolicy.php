<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Permissions\V1\Abilities;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->tokenCan(Abilities::CreatePost)) {
            return true;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->tokenCan(Abilities::UpdatePost)) {
            return true;
        } else if ($user->tokenCan(Abilities::UpdateOwnPost)) {
            return $user->id === $post->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->tokenCan(Abilities::DeletePost)) {
            return true;
        } else if ($user->tokenCan(Abilities::DeleteOwnPost)) {
            return $user->id === $post->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
