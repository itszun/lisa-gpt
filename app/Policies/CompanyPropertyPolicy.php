<?php

namespace App\Policies;

use App\Models\CompanyProperty;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_company::property');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyProperty $companyProperty): bool
    {
        return $user->can('view_company::property');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_company::property');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CompanyProperty $companyProperty): bool
    {
        return $user->can('update_company::property');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyProperty $companyProperty): bool
    {
        return $user->can('delete_company::property');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyProperty $companyProperty): bool
    {
        return $user->can('delete_any_company::property');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CompanyProperty $companyProperty): bool
    {
        return false;
    }
}
