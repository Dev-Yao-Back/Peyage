<?php

namespace App\Policies;

use App\Models\Provenance;
use App\Models\User;

class ProvenancetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('viewAny Provenance')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Provenance $provenance): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Provenance')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create Provenance')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Provenance $provenance): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Provenance')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Provenance $provenance): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete Provenance')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Provenance $provenance): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Provenance')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Provenance $provenance): bool
    {
        //
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Provenance')|| $user->can('All Permission');

    }
}