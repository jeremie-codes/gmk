<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Valve;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ValveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $priorites = ['basse', 'normale', 'haute', 'urgente'];

        for ($i = 0; $i < 20; $i++) {
            // Convert to Carbon instance
            $dateDebut = Carbon::instance($faker->dateTimeBetween('-2 months', '+1 month'));
            $dateFin = $faker->boolean(70) ? (clone $dateDebut)->addDays($faker->numberBetween(7, 60)) : null;
            $actif = $faker->boolean(80); // 80% de chance d'être actif

            Valve::create([
                'titre' => $faker->sentence(5),
                'contenu' => $faker->paragraph(3),
                'priorite' => $faker->randomElement($priorites),
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'actif' => $actif,
                'publie_par' => $users->random()->id,
            ]);
        }
    }
}
