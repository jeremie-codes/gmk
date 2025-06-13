<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoldeConge;
use App\Models\Agent;
use Carbon\Carbon;

class SoldeCongeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = Agent::all();
        $currentYear = Carbon::now()->year;

        if ($agents->isEmpty()) {
            $this->command->info('No agents found. Please run AgentSeeder first.');
            return;
        }

        foreach ($agents as $agent) {
            // Calculer le solde pour l'année en cours et l'année précédente
            for ($year = $currentYear - 1; $year <= $currentYear; $year++) {
                SoldeConge::mettreAJourSolde($agent, $year);
            }
        }
    }
}

