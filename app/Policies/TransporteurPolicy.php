<?php

namespace App\Policies;

use App\Models\Transporteur;
use App\Models\User;

class TransporteurPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire'])|| $user->can('viewAny Transporteur')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transporteur $transporteur): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Transporteur')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create Transporteur')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transporteur $transporteur): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Transporteur')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transporteur $transporteur): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete Transporteur')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transporteur $transporteur): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Transporteur')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transporteur $transporteur): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Transporteur')|| $user->can('All Permission');

    }
}