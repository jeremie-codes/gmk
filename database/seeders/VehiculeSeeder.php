<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicule;
use Faker\Factory as Faker;
use Carbon\Carbon;

class VehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $marques = ['Toyota', 'Mercedes-Benz', 'BMW', 'Renault', 'Peugeot', 'Hyundai', 'Kia'];
        $modeles = [
            'Corolla', 'Classe C', 'Série 3', 'Clio', '208', 'Tucson', 'Sportage',
            'Camry', 'Classe E', 'Série 5', 'Megane', '3008', 'Kona', 'Sorento'
        ];
        $types = ['Berline', '4x4', 'Utilitaire', 'Citadine', 'SUV'];
        $couleurs = ['Blanc', 'Noir', 'Gris', 'Bleu', 'Rouge', 'Argent'];

        for ($i = 0; $i < 20; $i++) {
            $marque = $faker->randomElement($marques);
            $modele = $faker->randomElement($modeles);
            $annee = $faker->numberBetween(2010, Carbon::now()->year);
            $kilometrage = $faker->numberBetween(10000, 300000);
            $dateAcquisition = $faker->dateTimeBetween('-10 years', '-1 year');
            $statut = $faker->randomElement(['bon_etat', 'panne', 'entretien', 'a_declasser']);

            Vehicule::create([
                'immatriculation' => $faker->unique()->bothify('AB-###-CD'),
                'marque' => $marque,
                'modele' => $modele,
                'type_vehicule' => $faker->randomElement($types),
                'annee' => $annee,
                'couleur' => $faker->randomElement($couleurs),
                'numero_chassis' => $faker->unique()->regexify('[A-Z0-9]{17}'),
                'numero_moteur' => $faker->unique()->regexify('[A-Z0-9]{10}'),
                'nombre_places' => $faker->numberBetween(2, 9),
                'kilometrage' => $kilometrage,
                'date_acquisition' => $dateAcquisition,
                'prix_acquisition' => $faker->randomFloat(2, 5000000, 30000000),
                'etat' => $statut,
                'date_derniere_visite_technique' => $faker->dateTimeBetween('-1 year', 'now'),
                'date_prochaine_visite_technique' => $faker->dateTimeBetween('now', '+1 year'),
                'date_derniere_vidange' => $faker->dateTimeBetween('-6 months', 'now'),
                'kilometrage_derniere_vidange' => $faker->numberBetween($kilometrage - 10000, $kilometrage),
                'observations' => $faker->paragraph(1),
                'photo' => null, // Vous pouvez ajouter un chemin de photo si vous en avez une par défaut
                'disponible' => $statut === 'bon_etat' ? $faker->boolean(80) : false, // 80% de chance d'être disponible si bon état
            ]);
        }
    }
}

