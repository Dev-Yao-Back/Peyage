<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin']) || $user->can('viewAny Utilisateur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole(['superadmin']) || $user->can('view Utilisateur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin']) || $user->can('create Utilisateur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole(['superadmin']) || $user->can('update Utilisateur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole(['superadmin']) || $user->can('delete Utilisateur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole(['superadmin'])|| $user->can('restore Utilisateur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole(['superadmin', ]) || $user->can('forceDelete Utilisateur')|| $user->can('All Permission');
    }
}