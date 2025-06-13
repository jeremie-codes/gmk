<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Récupérer les utilisateurs qui n'ont pas encore d'agent associé
        $usersWithoutAgent = User::doesntHave('agent')->get();

        // Créer des agents pour les utilisateurs existants sans agent
        foreach ($usersWithoutAgent as $user) {
            Agent::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'matricule' => 'MAT-' . $faker->unique()->randomNumber(5),
                    'nom' => $user->name,
                    'prenoms' => $faker->firstName(),
                    'date_naissance' => $faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                    'lieu_naissance' => $faker->city(),
                    'sexe' => $faker->randomElement(['M', 'F']),
                    'situation_matrimoniale' => $faker->randomElement(['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf(ve)']),
                    'direction' => $faker->randomElement(['Direction Générale', 'Direction RH', 'Direction Financière', 'Direction Technique', 'Direction Administrative', 'Direction Commerciale']),
                    'service' => $faker->randomElement(['Comptabilité', 'Informatique', 'Marketing', 'Ressources Humaines', 'Logistique', 'Commercial']),
                    'poste' => $faker->jobTitle(),
                    'salaire_base' => $faker->randomFloat(2, 500000, 2000000),
                    'date_recrutement' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                    'telephone' => $faker->phoneNumber(),
                    'email' => $user->email,
                    'photo' => null,
                    'adresse' => $faker->address(),
                    'compte_bancaire' => $faker->bankAccountNumber(),
                    'banque' => $faker->randomElement(['SGBCI', 'BICICI', 'Ecobank', 'BOA']),
                    'numero_cnps' => $faker->unique()->randomNumber(8),
                    'numero_impots' => $faker->unique()->randomNumber(9),
                    'statut' => 'actif',
                ]
            );
        }

        // Créer 20 agents supplémentaires sans compte utilisateur (pour simuler des agents existants avant la création de comptes)
        for ($i = 0; $i < 20; $i++) {
            Agent::updateOrCreate(
                ['matricule' => 'MAT-' . $faker->unique()->randomNumber(5)],
                [
                    'user_id' => null,
                    'nom' => $faker->lastName(),
                    'prenoms' => $faker->firstName(),
                    'date_naissance' => $faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                    'lieu_naissance' => $faker->city(),
                    'sexe' => $faker->randomElement(['M', 'F']),
                    'situation_matrimoniale' => $faker->randomElement(['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf(ve)']),
                    'direction' => $faker->randomElement(['Direction Générale', 'Direction RH', 'Direction Financière', 'Direction Technique', 'Direction Administrative', 'Direction Commerciale']),
                    'service' => $faker->randomElement(['Comptabilité', 'Informatique', 'Marketing', 'Ressources Humaines', 'Logistique', 'Commercial']),
                    'poste' => $faker->jobTitle(),
                    'salaire_base' => $faker->randomFloat(2, 500000, 2000000),
                    'date_recrutement' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                    'telephone' => $faker->phoneNumber(),
                    'email' => $faker->unique()->safeEmail(),
                    'photo' => null,
                    'adresse' => $faker->address(),
                    'compte_bancaire' => $faker->bankAccountNumber(),
                    'banque' => $faker->randomElement(['SGBCI', 'BICICI', 'Ecobank', 'BOA']),
                    'numero_cnps' => $faker->unique()->randomNumber(8),
                    'numero_impots' => $faker->unique()->randomNumber(9),
                    'statut' => $faker->randomElement(['actif', 'retraite', 'malade', 'demission']),
                ]
            );
        }
    }
}

