<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuiviCourrier;
use App\Models\Courrier;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SuiviCourrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $courriers = Courrier::all();
        $users = User::all();

        if ($courriers->isEmpty() || $users->isEmpty()) {
            $this->command->info('No courriers or users found. Please run CourrierSeeder and UserSeeder first.');
            return;
        }

        $actions = ['creation', 'en_cours', 'traite', 'archive', 'annule', 'modification', 'ajout_document'];

        foreach ($courriers as $courrier) {
            // Ajouter 3 à 7 suivis par courrier
            $numSuivis = $faker->numberBetween(3, 7);
            for ($i = 0; $i < $numSuivis; $i++) {
                $action = $faker->randomElement($actions);
                $commentaire = $faker->sentence();
                $effectuePar = $users->random()->id;

                SuiviCourrier::create([
                    'courrier_id' => $courrier->id,
                    'action' => $action,
                    'commentaire' => $commentaire,
                    'effectue_par' => $effectuePar,
                    'created_at' => $faker->dateTimeBetween($courrier->created_at, 'now'),
                    'updated_at' => $faker->dateTimeBetween($courrier->created_at, 'now'),
                ]);
            }
        }
    }
}

