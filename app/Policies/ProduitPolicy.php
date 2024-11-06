<?php

namespace App\Policies;

use App\Models\Produit;
use App\Models\User;

class ProduitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('viewAny Produit')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Produit $produit): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('view Produit')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('create Produit')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Produit $produit): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('update Produit')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Produit $produit): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('delete Produit')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Produit $produit): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('restore Produit')|| $user->can('All Permission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Produit $produit): bool
    {
        return $user->hasRole(['superadmin', 'Gestionnaire']) || $user->can('forceDelete Produit')|| $user->can('All Permission');
    }
}