<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffectationVehicule;
use App\Models\DemandeVehicule;
use App\Models\Vehicule;
use App\Models\Chauffeur;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AffectationVehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $demandesVehicules = DemandeVehicule::whereIn('statut', ['affecte', 'en_cours', 'termine'])->get();
        $vehicules = Vehicule::all();
        $chauffeurs = Chauffeur::all();
        $users = User::all();

        if ($demandesVehicules->isEmpty() || $vehicules->isEmpty() || $chauffeurs->isEmpty() || $users->isEmpty()) {
            $this->command->info('Not enough data to create AffectationVehicule. Ensure DemandeVehicule, Vehicule, Chauffeur, and User seeders have run.');
            return;
        }

        foreach ($demandesVehicules as $demande) {
            // S'assurer qu'il n'y a pas déjà une affectation pour cette demande
            if ($demande->affectation()->exists()) {
                continue;
            }

            $vehicule = $vehicules->random();
            $chauffeur = $chauffeurs->random();
            $affectePar = $users->random();

            $kilometrageDepart = $faker->numberBetween(10000, 200000);
            $carburantDepart = $faker->randomFloat(2, 20, 60);
            $etatVehiculeDepart = $faker->randomElement(['bon_etat', 'panne']);

            $kilometrageRetour = null;
            $carburantRetour = null;
            $carburantConsomme = null;
            $observationsRetour = null;
            $etatVehiculeRetour = null;
            $dateRetourEffective = null;
            $retourConfirme = false;

            if ($demande->statut === 'en_cours' || $demande->statut === 'termine') {
                $kilometrageRetour = $kilometrageDepart + $faker->numberBetween(50, 500);
                $carburantRetour = $faker->randomFloat(2, 5, $carburantDepart);
                $carburantConsomme = $carburantDepart - $carburantRetour;
                $observationsRetour = $faker->paragraph(1);
                $etatVehiculeRetour = $faker->randomElement(['bon_etat', 'panne']);
                $dateRetourEffective = $demande->date_heure_retour_effective ?? (clone $demande->date_heure_retour_prevue)->addMinutes($faker->numberBetween(-60, 60));
                $retourConfirme = true;
            }

            AffectationVehicule::create([
                'demande_vehicule_id' => $demande->id,
                'vehicule_id' => $vehicule->id,
                'chauffeur_id' => $chauffeur->id,
                'date_heure_affectation' => $demande->date_affectation ?? $faker->dateTimeBetween($demande->date_heure_sortie->subDays(2), $demande->date_heure_sortie),
                'kilometrage_depart' => $kilometrageDepart,
                'kilometrage_retour' => $kilometrageRetour,
                'carburant_depart' => $carburantDepart,
                'carburant_retour' => $carburantRetour,
                'carburant_consomme' => $carburantConsomme,
                'observations_depart' => $faker->paragraph(1),
                'observations_retour' => $observationsRetour,
                'etat_vehicule_depart' => $etatVehiculeDepart,
                'etat_vehicule_retour' => $etatVehiculeRetour,
                'affecte_par' => $affectePar->id,
                'date_retour_effective' => $dateRetourEffective,
                'retour_confirme' => $retourConfirme,
            ]);
        }
    }
}

