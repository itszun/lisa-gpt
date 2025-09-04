<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobOpeningPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_job::opening');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('view_job::opening');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_job::opening');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('update_job::opening');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('delete_job::opening');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('delete_any_job::opening');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('{{ ForceDelete }}');
    }
}
