<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['superadmin']) || $user->can('view roles')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['superadmin', 'Gestionnaire']) || $user->can('view roles')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create roles')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('edit roles')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete roles')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore roles')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('force delete roles')|| $user->can('All Permission');
    }
}