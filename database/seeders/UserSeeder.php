<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les rôles
        $roles = Role::all()->keyBy('name');

        // Créer les utilisateurs avec leurs rôles
        $users = [
            [
                'name' => 'Administrateur DRH',
                'email' => 'admin@anadec.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['drh']->id ?? null,
            ],
            [
                'name' => 'Gestionnaire RH',
                'email' => 'rh@anadec.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['rh']->id ?? null,
            ],
            [
                'name' => 'Directeur Général',
                'email' => 'directeur@anadec.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['directeur']->id ?? null,
            ],
            [
                'name' => 'Sous-Directeur',
                'email' => 'sousdirecteur@anadec.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['sous_directeur']->id ?? null,
            ],
            [
                'name' => 'Responsable Service',
                'email' => 'responsable@anadec.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['responsable_service']->id ?? null,
            ],
            [
                'name' => 'Agent Simple',
                'email' => 'agent@anadec.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['agent']->id ?? null,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}