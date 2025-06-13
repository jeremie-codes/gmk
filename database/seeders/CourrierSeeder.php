<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courrier;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CourrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $typesCourrier = ['entrant', 'sortant', 'interne'];
        $priorites = ['basse', 'normale', 'haute'];
        $statuts = ['recu', 'en_cours', 'traite', 'archive', 'annule'];

        for ($i = 0; $i < 50; $i++) {
            $typeCourrier = $faker->randomElement($typesCourrier);
            $priorite = $faker->randomElement($priorites);
            $statut = $faker->randomElement($statuts);
            $dateReception = null;
            $dateEnvoi = null;
            $dateTraitement = null;
            $traitePar = null;

            if ($typeCourrier === 'entrant' || $typeCourrier === 'interne') {
                $dateReception = $faker->dateTimeBetween('-6 months', 'now');
            }
            if ($typeCourrier === 'sortant' || $typeCourrier === 'interne') {
                $dateEnvoi = $faker->dateTimeBetween('-6 months', 'now');
            }

            if ($statut === 'traite' || $statut === 'archive') {
                $dateTraitement = $faker->dateTimeBetween($dateReception ?? $dateEnvoi ?? '-3 months', 'now');
                $traitePar = $users->random()->id;
            }

            Courrier::create([
                'reference' => Courrier::genererReference($typeCourrier),
                'objet' => $faker->sentence(),
                'type_courrier' => $typeCourrier,
                'expediteur' => $faker->company(),
                'destinataire' => $faker->company(),
                'date_reception' => $dateReception,
                'date_envoi' => $dateEnvoi,
                'date_traitement' => $dateTraitement,
                'statut' => $statut,
                'priorite' => $priorite,
                'description' => $faker->paragraph(2),
                'emplacement_physique' => $faker->bothify('Dossier ## - Étagère ??'),
                'chemin_fichier' => null, // Pas de fichier réel ici
                'confidentiel' => $faker->boolean(20),
                'enregistre_par' => $users->random()->id,
                'traite_par' => $traitePar,
                'commentaires' => $faker->paragraph(1),
            ]);
        }
    }
}

