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
        User::updateOrCreate(
            ['email' => 'admin@anadec.com'],
            [
                'name' => 'Admin GMK',
                'photo' => null, // Vous pouvez ajouter un chemin de photo si vous en avez une par défaut
                'password' => Hash::make('password'), // Mot de passe par défaut
            ]
        );

        // Créer un utilisateur RH
        User::updateOrCreate(
            ['email' => 'rh@anadec.com'],
            [
                'name' => 'RH GMK',
                'photo' => null,
                'password' => Hash::make('password'),
            ]
        );

        // Créer un utilisateur agent
        User::updateOrCreate(
            ['email' => 'agent@anadec.com'],
            [
                'name' => 'Agent Test',
                'photo' => null,
                'password' => Hash::make('password'),
            ]
        );

        // Créer 10 utilisateurs supplémentaires avec des rôles aléatoires
        for ($i = 0; $i < 10; $i++) {
            $user = User::updateOrCreate(
                ['email' => $faker->unique()->safeEmail()],
                [
                    'name' => $faker->name(),
                    'photo' => null,
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}

