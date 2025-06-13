<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceVehicule;
use App\Models\Vehicule;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon; // Ensure this is present

class MaintenanceVehiculeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $vehicules = Vehicule::all();
        $users = User::all();

        if ($vehicules->isEmpty() || $users->isEmpty()) {
            $this->command->info('No vehicules or users found. Please run VehiculeSeeder and UserSeeder first.');
            return;
        }

        $typesMaintenance = ['preventive', 'corrective', 'visite_technique', 'vidange', 'reparation'];
        $statuts = ['planifie', 'en_cours', 'termine', 'reporte'];

        foreach ($vehicules as $vehicule) {
            // Créer 5 maintenances par véhicule
            for ($i = 0; $i < 5; $i++) {
                $typeMaintenance = $faker->randomElement($typesMaintenance);
                // Convert to Carbon instance
                $dateMaintenance = Carbon::instance($faker->dateTimeBetween('-2 years', 'now'));
                $kilometrageMaintenance = $faker->numberBetween($vehicule->kilometrage - 50000, $vehicule->kilometrage);
                if ($kilometrageMaintenance < 0) $kilometrageMaintenance = 0; // Ensure non-negative
                $cout = $faker->randomFloat(2, 50000, 500000);
                $statut = $faker->randomElement($statuts);
                $dateProchaineMaintenance = null;
                $kilometrageProchainEntretien = null;

                if ($typeMaintenance === 'vidange') {
                    $dateProchaineMaintenance = (clone $dateMaintenance)->addMonths(6);
                    $kilometrageProchainEntretien = $kilometrageMaintenance + 10000;
                } elseif ($typeMaintenance === 'visite_technique') {
                    $dateProchaineMaintenance = (clone $dateMaintenance)->addYears(1);
                }

                MaintenanceVehicule::create([
                    'vehicule_id' => $vehicule->id,
                    'type_maintenance' => $typeMaintenance,
                    'date_maintenance' => $dateMaintenance,
                    'kilometrage_maintenance' => $kilometrageMaintenance,
                    'description' => $faker->sentence(),
                    'garage_atelier' => $faker->company(),
                    'cout' => $cout,
                    'pieces_changees' => $faker->boolean(70) ? $faker->words(3, true) : null,
                    'date_prochaine_maintenance' => $dateProchaineMaintenance,
                    'kilometrage_prochain_entretien' => $kilometrageProchainEntretien,
                    'statut' => $statut,
                    'observations' => $faker->paragraph(1),
                    'effectue_par' => $users->random()->id,
                ]);
            }
        }
    }
}
