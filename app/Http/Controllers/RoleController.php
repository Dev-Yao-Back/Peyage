<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function setupRolesAndPermissions()
    {
        // Créer des permissions
        $viewArticles = Permission::create(['name' => 'view']);
        $editArticles = Permission::create(['name' => 'edit ']);

        // Créer des rôles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Attribuer des permissions aux rôles
        $adminRole->givePermissionTo([$viewArticles, $editArticles]);
        $userRole->givePermissionTo($viewArticles);

        // Trouver un utilisateur Admin
        $adminUser = User::find(1); // Remplacez 1 par l'ID de l'utilisateur admin
        $adminUser->assignRole('admin'); // Assigner le rôle admin

        // Trouver un utilisateur standard
        $user = User::find(2); // Remplacez 2 par l'ID de l'utilisateur standard
        $user->assignRole('user'); // Assigner le rôle user
    }
}
