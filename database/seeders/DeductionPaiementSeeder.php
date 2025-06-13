<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeductionPaiement;
use App\Models\Paiement;
use Faker\Factory as Faker;

class DeductionPaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $paiements = Paiement::all();

        if ($paiements->isEmpty()) {
            $this->command->info('No payments found. Please run PaiementSeeder first.');
            return;
        }

        foreach ($paiements as $paiement) {
            // Ajouter 0 à 3 déductions par paiement
            $numDeductions = $faker->numberBetween(0, 3);
            for ($i = 0; $i < $numDeductions; $i++) {
                DeductionPaiement::create([
                    'paiement_id' => $paiement->id,
                    'libelle' => $faker->randomElement(['Impôts', 'Prêt', 'Avance sur salaire', 'Cotisation syndicale', 'Amende']),
                    'montant' => $faker->randomFloat(2, 1000, 50000),
                    'description' => $faker->sentence(),
                ]);
            }
        }
    }
}

