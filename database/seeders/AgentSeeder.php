<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $directions = [
            'Direction Générale',
            'Direction RH', 
            'Direction Financière',
            'Direction Technique',
            'Direction Administrative',
            'Direction Commerciale'
        ];

        $services = [
            'Service Personnel',
            'Service Paie',
            'Service Formation',
            'Service Comptabilité',
            'Service IT',
            'Service Marketing'
        ];

        $postes = [
            'Directeur',
            'Chef de Service',
            'Responsable',
            'Agent',
            'Assistant',
            'Technicien',
            'Secrétaire'
        ];

        $noms = [
            'KOUAME', 'KONE', 'YAO', 'ASSOUMOU', 'DIABATE', 'DOUMBIA', 
            'TRAORE', 'OUATTARA', 'BAMBA', 'TOURE', 'DIARRA', 'COULIBALY',
            'N\'GUESSAN', 'ADOU', 'AKISSI', 'KONAN', 'ADJOUA', 'AKA'
        ];

        $prenoms = [
            'Marie', 'Jean', 'Fatou', 'Amadou', 'Aissata', 'Moussa',
            'Fatoumata', 'Ibrahim', 'Rokia', 'Seydou', 'Aminata', 'Bakary',
            'Adama', 'Mariam', 'Ousmane', 'Kadiatou', 'Mamadou', 'Awa'
        ];

        // Générer 100 agents
        for ($i = 1; $i <= 100; $i++) {
            $dateNaissance = Carbon::createFromDate(
                rand(1960, 1995),
                rand(1, 12),
                rand(1, 28)
            );

            $dateRecrutement = Carbon::createFromDate(
                rand(2000, 2024),
                rand(1, 12),
                rand(1, 28)
            );

            $statut = $this->getRandomStatut();
            $agent = [
                'matricule' => 'MAT' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nom' => $noms[array_rand($noms)],
                'prenoms' => $prenoms[array_rand($prenoms)],
                'date_naissance' => $dateNaissance,
                'lieu_naissance' => $this->getRandomVille(),
                'sexe' => rand(0, 1) ? 'M' : 'F',
                'situation_matrimoniale' => $this->getRandomSituationMatrimoniale(),
                'direction' => $directions[array_rand($directions)],
                'service' => $services[array_rand($services)],
                'poste' => $postes[array_rand($postes)],
                'date_recrutement' => $dateRecrutement,
                'telephone' => '0' . rand(1, 9) . rand(10000000, 99999999),
                'email' => strtolower($prenoms[array_rand($prenoms)]) . '.' . 
                          strtolower($noms[array_rand($noms)]) . '@anadec.com',
                'adresse' => $this->getRandomAdresse(),
                'statut' => $statut,
            ];

            // Ajouter les dates spécifiques selon le statut
            if ($statut === 'retraite') {
                $agent['date_retraite'] = $dateRecrutement->copy()->addYears(rand(25, 35));
            } elseif ($statut === 'malade') {
                $agent['date_maladie'] = Carbon::now()->subDays(rand(1, 90));
            } elseif ($statut === 'demission') {
                $agent['date_demission'] = Carbon::now()->subDays(rand(1, 365));
            }

            Agent::create($agent);
        }
    }

    private function getRandomStatut()
    {
        $statuts = [
            'actif' => 70,      // 70% actifs
            'retraite' => 10,   // 10% retraités
            'malade' => 8,      // 8% malades
            'demission' => 5,   // 5% démissions
            'disponibilite' => 3, // 3% disponibilité
            'mission' => 2,     // 2% missions
            'detachement' => 1, // 1% détachements
            'mutation' => 1,    // 1% mutations
        ];

        $random = rand(1, 100);
        $cumul = 0;
        
        foreach ($statuts as $statut => $pourcentage) {
            $cumul += $pourcentage;
            if ($random <= $cumul) {
                return $statut;
            }
        }
        
        return 'actif';
    }

    private function getRandomVille()
    {
        $villes = [
            'Abidjan', 'Bouaké', 'Daloa', 'Yamoussoukro', 'San-Pédro',
            'Korhogo', 'Man', 'Divo', 'Gagnoa', 'Abengourou'
        ];
        
        return $villes[array_rand($villes)];
    }

    private function getRandomSituationMatrimoniale()
    {
        $situations = ['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf/Veuve'];
        return $situations[array_rand($situations)];
    }

    private function getRandomAdresse()
    {
        $quartiers = [
            'Cocody', 'Yopougon', 'Adjamé', 'Treichville', 'Marcory',
            'Port-Bouët', 'Koumassi', 'Plateau', 'Abobo', 'Attécoubé'
        ];
        
        return 'Quartier ' . $quartiers[array_rand($quartiers)] . ', Rue ' . rand(1, 50);
    }
}