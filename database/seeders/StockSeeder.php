<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use Faker\Factory as Faker;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $categories = [
            'Fournitures de bureau', 'Matériel informatique', 'Mobilier',
            'Produits d\'entretien', 'Consommables', 'Équipements', 'Papeterie', 'Autres'
        ];
        $unites = ['unité', 'paquet', 'boîte', 'ramette', 'cartouche', 'pièce', 'litre', 'kg'];

        for ($i = 0; $i < 30; $i++) {
            $nomArticle = $faker->word() . ' ' . $faker->randomElement(['papier', 'stylo', 'clavier', 'chaise', 'écran', 'nettoyant', 'agrafeuse']);
            $quantiteStock = $faker->numberBetween(0, 200);
            $quantiteMinimum = $faker->numberBetween(5, 50);
            $prixUnitaire = $faker->randomFloat(2, 100, 50000);

            Stock::create([
                'nom_article' => $nomArticle,
                'description' => $faker->sentence(),
                'reference' => 'REF-' . $faker->unique()->randomNumber(6),
                'categorie' => $faker->randomElement($categories),
                'quantite_stock' => $quantiteStock,
                'quantite_minimum' => $quantiteMinimum,
                'unite' => $faker->randomElement($unites),
                'prix_unitaire' => $prixUnitaire,
                'fournisseur' => $faker->company(),
                'date_derniere_entree' => $faker->dateTimeBetween('-1 year', 'now'),
                'quantite_derniere_entree' => $faker->numberBetween(10, 100),
                'emplacement' => $faker->bothify('Allée ## - Étagère ??'),
                'statut' => ($quantiteStock <= 0) ? 'rupture' : (($quantiteStock <= $quantiteMinimum) ? 'alerte' : 'disponible'),
            ]);
        }
    }
}

