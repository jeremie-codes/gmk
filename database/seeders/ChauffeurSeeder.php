<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chauffeur;
use App\Models\Agent;
use Faker\Factory as Faker;
use Carbon\Carbon; // Ensure this is present

class ChauffeurSeeder extends Seeder
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

        // Filtrer les agents qui ne sont pas déjà chauffeurs
        $eligibleAgents = $agents->filter(function ($agent) {
            return !$agent->chauffeur;
        });

        if ($eligibleAgents->isEmpty()) {
            $this->command->info('No eligible agents found to be assigned as chauffeurs.');
            return;
        }

        foreach ($eligibleAgents as $agent) {
            // Retirer l'agent de la liste pour éviter les doublons
            // This line is not needed if you are iterating over $eligibleAgents directly
            // $eligibleAgents = $eligibleAgents->where('id', '!=', $agent->id);

            $dateObtentionPermis = Carbon::instance($faker->dateTimeBetween('-20 years', '-2 years')); // Convert to Carbon
            $dateExpirationPermis = (clone $dateObtentionPermis)->addYears($faker->numberBetween(5, 10));
            $statut = $faker->randomElement(['actif', 'suspendu', 'inactif']);

            Chauffeur::create([
                'agent_id' => $agent->id,
                'numero_permis' => $faker->unique()->regexify('[A-Z]{2}[0-9]{5}'),
                'categorie_permis' => $faker->randomElement(['A', 'B', 'C', 'D', 'E']),
                'date_obtention_permis' => $dateObtentionPermis,
                'date_expiration_permis' => $dateExpirationPermis,
                'experience_annees' => $faker->numberBetween(1, 15),
                'statut' => $statut,
                'observations' => $faker->paragraph(1),
                'disponible' => $statut === 'actif' ? $faker->boolean(80) : false,
            ]);
        }
    }
}
