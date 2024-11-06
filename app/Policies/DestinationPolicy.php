<?php

namespace App\Policies;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DestinationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire'])|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Destination $destination): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Destination')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Destination')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Destination $destination): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Destination')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Destination $destination): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete Destination')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Destination $destination): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Destination')|| $user->can('All Permission');

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Destination $destination): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Destination')|| $user->can('All Permission');

    }
}