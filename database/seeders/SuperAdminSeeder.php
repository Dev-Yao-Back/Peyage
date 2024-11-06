<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer les rôles
        $superadmin = Role::create(['name' => 'superadmin']);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),

        ]);


          $user->assignRole($superadmin);

          $admin = Role::create(['name' => 'admin']);

          $user = User::create([
              'name' => ' Admin',
              'email' => 'admin@gmail.com',
              'password' => bcrypt('password'),

          ]);


            $user->assignRole($admin);

          $gestionnaire = Role::create(['name' => 'Gestionnaire']);

        $user = User::create([
            'name' => 'Gestionnaire',
            'email' => 'gestionnaire@gmail.com',
            'password' => bcrypt('password'),

        ]);


          $user->assignRole($gestionnaire);
}}