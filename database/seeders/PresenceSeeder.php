<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presence;
use App\Models\Agent;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $agents = Agent::all();

        if ($agents->isEmpty()) {
            $this->command->info('No agents found. Please run AgentSeeder first.');
            return;
        }

        foreach ($agents as $agent) {
            // Générer des présences pour les 30 derniers jours
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::today()->subDays($i);
                $statut = $faker->randomElement(['present', 'present_retard', 'justifie', 'absent']);
                $heureArrivee = null;
                $heureDepart = null;
                $motif = null;

                if ($statut === 'present' || $statut === 'present_retard') {
                    $heureArrivee = $faker->dateTimeBetween($date->format('Y-m-d 07:00:00'), $date->format('Y-m-d 09:00:00'))->format('H:i:s');
                    $heureDepart = $faker->dateTimeBetween($date->format('Y-m-d 16:00:00'), $date->format('Y-m-d 18:00:00'))->format('H:i:s');
                }

                if ($statut === 'present_retard') {
                    $motif = 'Retard';
                } elseif ($statut === 'justifie') {
                    $motif = $faker->randomElement(['Rendez-vous médical', 'Formation', 'Mission extérieure']);
                } elseif ($statut === 'absent') {
                    $motif = $faker->randomElement(['Maladie', 'Absence non justifiée', 'Problème personnel']);
                }

                // Utiliser updateOrCreate pour éviter les doublons si le seeder est exécuté plusieurs fois
                Presence::updateOrCreate(
                    [
                        'agent_id' => $agent->id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'heure_arrivee' => $heureArrivee,
                        'heure_depart' => $heureDepart,
                        'statut' => $statut,
                        'motif' => $motif,
                    ]
                );
            }
        }
    }
}

