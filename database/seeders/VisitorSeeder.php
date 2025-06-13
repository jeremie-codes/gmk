<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class VisitorSeeder extends Seeder
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

        $types = ['entrepreneur', 'visiteur'];
        $directions = [
            'Direction Générale', 'Direction RH', 'Direction Financière',
            'Direction Technique', 'Direction Administrative', 'Direction Commerciale'
        ];

        for ($i = 0; $i < 50; $i++) {
            $type = $faker->randomElement($types);
            // Convert to Carbon instance
            $heureArrivee = Carbon::instance($faker->dateTimeBetween('-3 months', 'now'));
            $heureDepart = null;
            $estEnCours = $faker->boolean(30); // 30% de chance que la visite soit encore en cours

            if (!$estEnCours) {
                $heureDepart = (clone $heureArrivee)->addMinutes($faker->numberBetween(30, 240));
            }

            Visitor::create([
                'nom' => $faker->name(),
                'type' => $type,
                'motif' => $faker->sentence(),
                'direction' => $faker->randomElement($directions),
                'destination' => $faker->word() . ' Bureau',
                'heure_arrivee' => $heureArrivee,
                'heure_depart' => $heureDepart,
                'observations' => $faker->paragraph(1),
                'piece_identite' => $faker->boolean(70) ? $faker->unique()->bothify('ID-########') : null,
                'enregistre_par' => $users->random()->id,
            ]);
        }
    }
}
