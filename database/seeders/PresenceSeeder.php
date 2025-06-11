<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Presence;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PresenceSeeder extends Seeder
{
    public function run(): void
    {
        $agents = Agent::where('statut', 'actif')->get();
        
        // Générer les présences pour les 30 derniers jours
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Ignorer les weekends
            if ($date->isWeekend()) {
                continue;
            }
            
            foreach ($agents as $agent) {
                // 85% de chance d'avoir une présence enregistrée
                if (rand(1, 100) <= 85) {
                    $statut = $this->getRandomStatut();
                    $heureArrivee = null;
                    $heureDepart = null;
                    
                    if (in_array($statut, ['present', 'present_retard'])) {
                        $heureArrivee = $this->getRandomHeureArrivee($statut);
                        $heureDepart = $this->getRandomHeureDepart();
                    }
                    
                    Presence::create([
                        'agent_id' => $agent->id,
                        'date' => $date,
                        'heure_arrivee' => $heureArrivee,
                        'heure_depart' => $heureDepart,
                        'statut' => $statut,
                        'motif' => $this->getRandomMotif($statut),
                    ]);
                }
            }
        }
    }
    
    private function getRandomStatut()
    {
        $statuts = [
            'present' => 75,           // 75% présents
            'present_retard' => 10,    // 10% présents avec retard
            'justifie' => 5,           // 5% absence justifiée
            'absence_autorisee' => 5,  // 5% absence autorisée
            'absent' => 5,             // 5% absents
        ];
        
        $random = rand(1, 100);
        $cumul = 0;
        
        foreach ($statuts as $statut => $pourcentage) {
            $cumul += $pourcentage;
            if ($random <= $cumul) {
                return $statut;
            }
        }
        
        return 'present';
    }
    
    private function getRandomHeureArrivee($statut)
    {
        if ($statut === 'present') {
            // Arrivée normale entre 7h30 et 8h00
            $heure = rand(7, 7);
            $minute = rand(30, 59);
            if ($heure === 7 && $minute < 30) {
                $minute = rand(30, 59);
            }
            if ($heure === 8) {
                $minute = rand(0, 0);
            }
        } else {
            // Retard entre 8h01 et 10h00
            $heure = rand(8, 9);
            $minute = rand(1, 59);
        }
        
        return sprintf('%02d:%02d', $heure, $minute);
    }
    
    private function getRandomHeureDepart()
    {
        // Départ entre 16h00 et 18h00
        $heure = rand(16, 17);
        $minute = rand(0, 59);
        
        return sprintf('%02d:%02d', $heure, $minute);
    }
    
    private function getRandomMotif($statut)
    {
        $motifs = [
            'justifie' => [
                'Rendez-vous médical',
                'Problème familial',
                'Urgence personnelle',
                'Problème de transport',
            ],
            'absence_autorisee' => [
                'Congé annuel',
                'Permission exceptionnelle',
                'Formation externe',
                'Mission officielle',
            ],
            'absent' => [
                null, // Pas de motif pour les absences non justifiées
            ],
        ];
        
        if (!isset($motifs[$statut])) {
            return null;
        }
        
        $motifsList = $motifs[$statut];
        return $motifsList[array_rand($motifsList)];
    }
}