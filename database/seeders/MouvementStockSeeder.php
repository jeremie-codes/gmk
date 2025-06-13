<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MouvementStock;
use App\Models\Stock;
use App\Models\DemandeFourniture;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MouvementStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $stocks = Stock::all();
        $demandesFournitures = DemandeFourniture::all();
        $users = User::all();

        if ($stocks->isEmpty() || $users->isEmpty()) {
            $this->command->info('No stocks or users found. Please run StockSeeder and UserSeeder first.');
            return;
        }

        foreach ($stocks as $stock) {
            $currentQuantity = $stock->quantite_stock;

            // Générer 10 mouvements par stock
            for ($i = 0; $i < 10; $i++) {
                $typeMouvement = $faker->randomElement(['entree', 'sortie', 'ajustement']);
                $quantite = $faker->numberBetween(1, 50);
                $motif = $faker->sentence(3);
                $demandeFournitureId = null;

                $quantiteAvant = $currentQuantity;

                if ($typeMouvement === 'entree') {
                    $currentQuantity += $quantite;
                } elseif ($typeMouvement === 'sortie') {
                    // S'assurer que la quantité ne devient pas négative
                    $quantite = min($quantite, $currentQuantity);
                    $currentQuantity -= $quantite;
                    // Associer à une demande de fourniture si disponible
                    if ($demandesFournitures->isNotEmpty() && $faker->boolean(50)) {
                        $demandeFournitureId = $demandesFournitures->random()->id;
                    }
                } else { // ajustement
                    if ($faker->boolean(50)) { // Ajustement positif
                        $currentQuantity += $quantite;
                        $motif = 'Ajustement positif';
                    } else { // Ajustement négatif
                        $quantite = min($quantite, $currentQuantity);
                        $currentQuantity -= $quantite;
                        $motif = 'Ajustement négatif';
                    }
                }

                MouvementStock::create([
                    'stock_id' => $stock->id,
                    'demande_fourniture_id' => $demandeFournitureId,
                    'type_mouvement' => $typeMouvement,
                    'quantite' => $quantite,
                    'quantite_avant' => $quantiteAvant,
                    'quantite_apres' => $currentQuantity,
                    'motif' => $motif,
                    'effectue_par' => $users->random()->id,
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                ]);
            }
            // Mettre à jour la quantité finale du stock
            $stock->update(['quantite_stock' => $currentQuantity]);
            $stock->mettreAJourStatut();
        }
    }
}

