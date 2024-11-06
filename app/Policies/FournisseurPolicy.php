<?php

namespace App\Policies;

use App\Models\Fournisseur; // Correction ici
use App\Models\User;

class FournisseurPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('viewAny Fournisseur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fournisseur $fournisseur): bool // Correction ici
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Fournisseur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create Fournisseur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fournisseur $fournisseur): bool // Correction ici
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Fournisseur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fournisseur $fournisseur): bool // Correction ici
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete Fournisseur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fournisseur $fournisseur): bool // Correction ici
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Fournisseur')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fournisseur $fournisseur): bool // Correction ici
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Fournisseur')|| $user->can('All Permission');
    }
}