<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DemandeVehicule;
use App\Models\Agent;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DemandeVehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $agents = Agent::all();
        $vehicules = Vehicule::all();
        $chauffeurs = Chauffeur::all();
        $users = User::all();

        if ($agents->isEmpty() || $users->isEmpty()) {
            $this->command->info('No agents or users found. Please run AgentSeeder and UserSeeder first.');
            return;
        }

        $directions = [
            'Direction Générale', 'Direction RH', 'Direction Financière',
            'Direction Technique', 'Direction Administrative', 'Direction Commerciale'
        ];

        for ($i = 0; $i < 40; $i++) {
            $agent = $agents->random();
            $vehicule = $vehicules->isNotEmpty() ? $vehicules->random() : null;
            $chauffeur = $chauffeurs->isNotEmpty() ? $chauffeurs->random() : null;
            $dateHeureSortie = Carbon::instance($faker->dateTimeBetween('-2 months', '+2 months')); // Convert to Carbon
            $dureePrevue = $faker->numberBetween(1, 24); // en heures
            $dateHeureRetourPrevue = (clone $dateHeureSortie)->addHours($dureePrevue);
            $urgence = $faker->randomElement(['faible', 'normale', 'elevee', 'critique']);
            $statut = $faker->randomElement(['en_attente', 'approuve', 'affecte', 'en_cours', 'termine', 'rejete']);
            $approuvePar = null;
            $dateApprobation = null;
            $commentaireApprobateur = null;
            $commentaireAffectation = null;
            $dateAffectation = null;
            $dateHeureRetourEffective = null;

            if ($statut !== 'en_attente' && $statut !== 'rejete') {
                $approuvePar = $users->random()->id;
                $dateApprobation = (clone $dateHeureSortie)->subDays($faker->numberBetween(1, 5)); // $dateHeureSortie is already Carbon
                $commentaireApprobateur = $faker->sentence();
            }

            if ($statut === 'affecte' || $statut === 'en_cours' || $statut === 'termine') {
                if ($vehicule && $chauffeur) {
                    $commentaireAffectation = $faker->sentence();
                    // Ensure $dateApprobation is Carbon before cloning
                    $dateAffectation = (clone Carbon::instance($dateApprobation))->addDays($faker->numberBetween(1, 3));
                } else {
                    // Si pas de véhicule/chauffeur, on ne peut pas être affecté/en cours/terminé
                    $statut = 'approuve';
                }
            }

            if ($statut === 'termine') {
                // Ensure $dateHeureRetourPrevue is Carbon before cloning
                $dateHeureRetourEffective = (clone Carbon::instance($dateHeureRetourPrevue))->addMinutes($faker->numberBetween(-60, 60));
            }

            DemandeVehicule::create([
                'agent_id' => $agent->id,
                'vehicule_id' => $vehicule ? $vehicule->id : null,
                'chauffeur_id' => $chauffeur ? $chauffeur->id : null,
                'direction' => $faker->randomElement($directions),
                'service' => $faker->word() . ' Service',
                'destination' => $faker->city(),
                'motif' => $faker->sentence(),
                'date_heure_sortie' => $dateHeureSortie,
                'date_heure_retour_prevue' => $dateHeureRetourPrevue,
                'date_heure_retour_effective' => $dateHeureRetourEffective,
                'duree_prevue' => $dureePrevue,
                'nombre_passagers' => $faker->numberBetween(1, 5),
                'urgence' => $urgence,
                'statut' => $statut,
                'justification' => $faker->paragraph(1),
                'commentaire_approbateur' => $commentaireApprobateur,
                'date_approbation' => $dateApprobation,
                'approuve_par' => $approuvePar,
                'commentaire_affectation' => $commentaireAffectation,
                'date_affectation' => $dateAffectation,
            ]);
        }
    }
}

