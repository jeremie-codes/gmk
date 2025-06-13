<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DemandeFourniture;
use App\Models\Agent;
use App\Models\Stock;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon; // Add this line

class DemandeFournitureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $agents = Agent::all();
        $stocks = Stock::all();
        $users = User::all();

        if ($agents->isEmpty() || $stocks->isEmpty() || $users->isEmpty()) {
            $this->command->info('No agents, stocks or users found. Please run AgentSeeder, StockSeeder and UserSeeder first.');
            return;
        }

        foreach ($agents as $agent) {
            for ($i = 0; $i < 5; $i++) {
                $dateBesoin = $faker->dateTimeBetween('-3 months', '+3 months');
                $statut = $faker->randomElement(['en_attente', 'approuve', 'en_cours', 'livre', 'rejete']);
                $approuvePar = null;
                $dateApprobation = null;
                $commentaireApprobateur = null;
                $dateLivraison = null;
                $commentaireLivraison = null;

                if ($statut !== 'en_attente' && $statut !== 'rejete') {
                    $approuvePar = $users->random()->id;
                    // Convertir $dateBesoin en instance Carbon avant d'appeler addDays()
                    $dateApprobation = Carbon::instance($dateBesoin)->addDays($faker->numberBetween(1, 5));
                    $commentaireApprobateur = $faker->sentence();
                }

                if ($statut === 'livre') {
                    // Convertir $dateApprobation en instance Carbon avant d'appeler addDays()
                    $dateLivraison = Carbon::instance($dateApprobation)->addDays($faker->numberBetween(1, 3));
                    $commentaireLivraison = $faker->sentence();
                }

                DemandeFourniture::create([
                    'agent_id' => $agent->id,
                    'article_id' => $stocks->random()->id,
                    'direction' => $faker->randomElement(['Direction Générale', 'Direction RH', 'Direction Financière', 'Direction Technique', 'Direction Administrative', 'Direction Commerciale']),
                    'service' => $faker->word(),
                    'besoin' => $faker->sentence(),
                    'quantite' => $faker->numberBetween(1, 10),
                    'unite' => $faker->randomElement(['unité', 'paquet', 'boîte', 'ramette']),
                    'urgence' => $faker->randomElement(['faible', 'normale', 'elevee', 'critique']),
                    'statut' => $statut,
                    'date_besoin' => $dateBesoin->format('Y-m-d'),
                    'justification' => $faker->paragraph(),
                    'commentaire_approbateur' => $commentaireApprobateur,
                    'date_approbation' => $dateApprobation,
                    'approuve_par' => $approuvePar,
                    'date_livraison' => $dateLivraison,
                    'commentaire_livraison' => $commentaireLivraison,
                ]);
            }
        }
    }
}

