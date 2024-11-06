<?php

namespace App\Policies;

use App\Models\Campagne;
use App\Models\User;

class CampagnePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('viewAny Campagne')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campagne $campagne): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Campagne')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create Campagne')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campagne $campagne): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Campagne')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campagne $campagne): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire'])|| $user->can('delete Campagne')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campagne $campagne): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Campagne')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campagne $campagne): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Campagne')|| $user->can('All Permission');

    }
}