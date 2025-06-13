<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AgentSeeder::class,
            PresenceSeeder::class,
            CongeSeeder::class,
            SoldeCongeSeeder::class,
            CotationAgentSeeder::class,
            StockSeeder::class,
            DemandeFournitureSeeder::class,
            MouvementStockSeeder::class,
            VehiculeSeeder::class,
            ChauffeurSeeder::class,
            DemandeVehiculeSeeder::class,
            AffectationVehiculeSeeder::class,
            MaintenanceVehiculeSeeder::class,
            PaiementSeeder::class,
            DeductionPaiementSeeder::class,
            PrimePaiementSeeder::class,
            CourrierSeeder::class,
            DocumentCourrierSeeder::class,
            SuiviCourrierSeeder::class,
            VisitorSeeder::class,
            ValveSeeder::class,
        ]);
    }
}

