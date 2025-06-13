<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paiement;
use App\Models\Agent;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $agents = Agent::all();
        $users = User::all();

        if ($agents->isEmpty() || $users->isEmpty()) {
            $this->command->info('No agents or users found. Please run AgentSeeder and UserSeeder first.');
            return;
        }

        $typesPaiement = ['salaire', 'prime', 'indemnite', 'avance', 'solde_tout_compte', 'autre'];
        $methodesPaiement = ['virement', 'cheque', 'especes', 'mobile_money', 'autre'];

        foreach ($agents as $agent) {
            // Créer des paiements pour les 12 derniers mois
            for ($i = 0; $i < 12; $i++) {
                $datePaiement = Carbon::now()->subMonths($i)->endOfMonth();
                $moisConcerne = $datePaiement->month;
                $anneeConcernee = $datePaiement->year;

                $typePaiement = $faker->randomElement($typesPaiement);
                $montantBrut = $faker->randomFloat(2, 300000, 2500000);
                $montantNet = $montantBrut * $faker->randomFloat(2, 0.8, 0.95); // Net est 80-95% du brut
                $statut = $faker->randomElement(['en_attente', 'valide', 'paye', 'annule']);
                $methodePaiement = null;
                $referencePaiement = null;
                $validePar = null;
                $dateValidation = null;

                if ($statut === 'valide' || $statut === 'paye') {
                    $validePar = $users->random()->id;
                    $dateValidation = (clone $datePaiement)->subDays($faker->numberBetween(1, 5));
                }

                if ($statut === 'paye') {
                    $methodePaiement = $faker->randomElement($methodesPaiement);
                    $referencePaiement = $faker->unique()->bothify('REF-########');
                }

                Paiement::create([
                    'agent_id' => $agent->id,
                    'type_paiement' => $typePaiement,
                    'montant_brut' => $montantBrut,
                    'montant_net' => $montantNet,
                    'date_paiement' => $datePaiement,
                    'mois_concerne' => $moisConcerne,
                    'annee_concernee' => $anneeConcernee,
                    'statut' => $statut,
                    'methode_paiement' => $methodePaiement,
                    'reference_paiement' => $referencePaiement,
                    'commentaire' => $faker->paragraph(1),
                    'cree_par' => $users->random()->id,
                    'valide_par' => $validePar,
                    'date_validation' => $dateValidation,
                ]);
            }
        }
    }
}

