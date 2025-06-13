<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrimePaiement;
use App\Models\Paiement;
use Faker\Factory as Faker;

class PrimePaiementSeeder extends Seeder
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
            // Ajouter 0 à 3 primes par paiement
            $numPrimes = $faker->numberBetween(0, 3);
            for ($i = 0; $i < $numPrimes; $i++) {
                PrimePaiement::create([
                    'paiement_id' => $paiement->id,
                    'libelle' => $faker->randomElement(['Prime de transport', 'Prime de logement', 'Prime de performance', 'Bonus annuel', 'Indemnité de repas']),
                    'montant' => $faker->randomFloat(2, 5000, 100000),
                    'description' => $faker->sentence(),
                ]);
            }
        }
    }
}

