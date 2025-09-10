<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_chat');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chats): bool
    {
        return $user->can('view_chat');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_chat');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chat $chats): bool
    {
        return $user->can('update_chat');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chats): bool
    {
        return $user->can('delete_chat');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chat $chats): bool
    {
        return $user->can('delete_any_chat');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chat $chats): bool
    {
        return $user->can('{{ ForceDelete }}');
    }
}
