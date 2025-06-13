<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Créer un utilisateur administrateur (DRH)
        $drhRole = Role::where('name', 'drh')->first();
        User::updateOrCreate(
            ['email' => 'admin@anadec.com'],
            [
                'name' => 'Admin ANADEC',
                'photo' => null, // Vous pouvez ajouter un chemin de photo si vous en avez une par défaut
                'password' => Hash::make('password'), // Mot de passe par défaut
                'role_id' => $drhRole ? $drhRole->id : null,
            ]
        );

        // Créer un utilisateur RH
        $rhRole = Role::where('name', 'rh')->first();
        User::updateOrCreate(
            ['email' => 'rh@anadec.com'],
            [
                'name' => 'RH ANADEC',
                'photo' => null,
                'password' => Hash::make('password'),
                'role_id' => $rhRole ? $rhRole->id : null,
            ]
        );

        // Créer un utilisateur agent
        $agentRole = Role::where('name', 'agent')->first();
        User::updateOrCreate(
            ['email' => 'agent@anadec.com'],
            [
                'name' => 'Agent Test',
                'photo' => null,
                'password' => Hash::make('password'),
                'role_id' => $agentRole ? $agentRole->id : null,
            ]
        );

        // Créer 10 utilisateurs supplémentaires avec des rôles aléatoires
        $roles = Role::all();
        for ($i = 0; $i < 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => $faker->unique()->safeEmail()],
                [
                    'name' => $faker->name(),
                    'photo' => null,
                    'password' => Hash::make('password'),
                    'role_id' => $roles->random()->id,
                ]
            );
        }
    }
}

