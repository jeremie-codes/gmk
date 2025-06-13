<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentCourrier;
use App\Models\Courrier;
use App\Models\User;
use Faker\Factory as Faker;

class DocumentCourrierSeeder extends Seeder
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

        $extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];

        foreach ($courriers as $courrier) {
            // Ajouter 0 à 3 documents par courrier
            $numDocuments = $faker->numberBetween(0, 3);
            for ($i = 0; $i < $numDocuments; $i++) {
                $extension = $faker->randomElement($extensions);
                $nomDocument = $faker->word() . '.' . $extension;
                $cheminFichier = 'courriers/' . $courrier->id . '/' . $faker->uuid() . '.' . $extension;
                $tailleFichier = $faker->numberBetween(10000, 10000000); // 10KB to 10MB

                DocumentCourrier::create([
                    'courrier_id' => $courrier->id,
                    'nom_document' => $nomDocument,
                    'type_document' => DocumentCourrier::detecterType($extension),
                    'chemin_fichier' => $cheminFichier,
                    'taille_fichier' => $tailleFichier,
                    'ajoute_par' => $users->random()->id,
                    'description' => $faker->sentence(),
                ]);
            }
        }
    }
}

