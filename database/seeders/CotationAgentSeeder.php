<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CotationAgent;
use App\Models\Agent;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CotationAgentSeeder extends Seeder
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
            // Générer des cotations pour les 4 derniers trimestres
            for ($i = 0; $i < 4; $i++) {
                $periodeFin = Carbon::now()->subMonths($i * 3)->endOfQuarter();
                $periodeDebut = (clone $periodeFin)->startOfQuarter();

                // Assurez-vous que la période ne dépasse pas la date de recrutement de l'agent
                if ($periodeDebut->lt($agent->date_recrutement)) {
                    $periodeDebut = $agent->date_recrutement;
                }
                if ($periodeFin->lt($periodeDebut)) {
                    continue; // Skip if period is invalid
                }

                CotationAgent::enregistrerCotation(
                    $agent,
                    $periodeDebut->format('Y-m-d'),
                    $periodeFin->format('Y-m-d'),
                    $faker->paragraph(2)
                );
            }
        }
    }
}

