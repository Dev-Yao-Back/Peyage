<?php

namespace App\Policies;

use App\Models\Operation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OperationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('viewAny Operation')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Operation $operation): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Operation')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create Operation')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Operation $operation): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Operation')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Operation $operation): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete Operation')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Operation $operation): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Operation')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Operation $operation): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Operation')|| $user->can('All Permission');

    }
}