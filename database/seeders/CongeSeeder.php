<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conge;
use App\Models\Agent;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon; // Add this line

class CongeSeeder extends Seeder
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

        foreach ($agents as $agent) {
            // Créer 5 demandes de congé par agent
            for ($i = 0; $i < 5; $i++) {
                $dateDebut = $faker->dateTimeBetween('-1 year', '+6 months');
                // Convertir $dateDebut en instance Carbon avant d'appeler addDays()
                $dateFin = Carbon::instance($dateDebut)->addDays($faker->numberBetween(5, 30));
                $type = $faker->randomElement(['annuel', 'maladie', 'maternite', 'paternite', 'exceptionnel']);
                $statut = $faker->randomElement(['en_attente', 'approuve_directeur', 'valide_drh', 'rejete', 'traiter_rh']);
                $justificatif = null;
                $commentaireDirecteur = null;
                $commentaireDrh = null;
                $approuveParDirecteur = null;
                $traiterParRh = null;
                $valideParDrh = null;
                $dateApprobationDirecteur = null;
                $dateTraiterRh = null;
                $dateValidationDrh = null;

                if ($type !== 'annuel' && $faker->boolean(70)) { // 70% de chance d'avoir un justificatif pour les non-annuels
                    // Simuler un chemin de fichier pour le justificatif
                    $justificatif = 'conges/justificatifs/' . $faker->uuid() . '.pdf';
                }

                if ($statut === 'approuve_directeur' || $statut === 'traiter_rh' || $statut === 'valide_drh' || $statut === 'rejete') {
                    $approuveParDirecteur = $users->random()->id;
                    // Convertir $dateDebut en instance Carbon avant d'appeler subDays()
                    $dateApprobationDirecteur = Carbon::instance($dateDebut)->subDays($faker->numberBetween(1, 5));
                    $dateTraiterRh = Carbon::instance($dateDebut)->subDays($faker->numberBetween(1, 5));
                    $commentaireDirecteur = $faker->sentence();
                }

                if ($statut === 'traite_rh' || $statut === 'rejete') {
                    $traiterParRh = $users->random()->id;
                    // Convertir $dateTraiterRh en instance Carbon avant d'appeler addDays()
                    $dateValidationDrh = Carbon::instance($dateTraiterRh)->addDays($faker->numberBetween(1, 3));
                    $commentaireDrh = $faker->sentence();
                }

                if ($statut === 'valide_drh' || $statut === 'rejete') {
                    $valideParDrh = $users->random()->id;
                    // Convertir $dateApprobationDirecteur en instance Carbon avant d'appeler addDays()
                    $dateValidationDrh = Carbon::instance($dateApprobationDirecteur)->addDays($faker->numberBetween(1, 3));
                    $commentaireDrh = $faker->sentence();
                }

                Conge::create([
                    'agent_id' => $agent->id,
                    'date_debut' => $dateDebut->format('Y-m-d'),
                    'date_fin' => $dateFin->format('Y-m-d'),
                    'nombre_jours' => Conge::calculerNombreJours($dateDebut, $dateFin),
                    'motif' => $faker->sentence(),
                    'justificatif' => $justificatif,
                    'type' => $type,
                    'statut' => $statut,
                    'commentaire_directeur' => $commentaireDirecteur,
                    'commentaire_drh' => $commentaireDrh,
                    'date_approbation_directeur' => $dateApprobationDirecteur,
                    'date_validation_drh' => $dateValidationDrh,
                    'date_traiter_rh' => $dateValidationDrh,
                    'approuve_par_directeur' => $approuveParDirecteur,
                    'traiter_par_rh' => $traiterParRh,
                    'valide_par_drh' => $valideParDrh,
                ]);
            }
        }
    }
}

