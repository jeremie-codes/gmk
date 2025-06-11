<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SoldeConge extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'annee',
        'jours_acquis',
        'jours_pris',
        'jours_restants',
        'date_calcul',
    ];

    protected $casts = [
        'date_calcul' => 'date',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Méthodes de calcul avec la formule correcte
    public static function calculerSolde(Agent $agent, $annee = null)
    {
        $annee = $annee ?: now()->year;

        // Calculer l'ancienneté en années complètes au 31 décembre de l'année
        $dateRecrutement = $agent->date_recrutement;
        $finAnnee = Carbon::createFromDate($annee, 12, 31);

        // Vérifier si l'agent a au moins 1 an de service au 31 décembre
        if ($dateRecrutement->year >= $annee) {
            // Agent recruté dans l'année en cours, pas encore d'ancienneté
            $anneesAnciennete = 0;
        } else {
            // Calculer les années complètes d'ancienneté
            $anneesAnciennete = floor($dateRecrutement->diffInYears($finAnnee));
        }

        // Formule : 30 jours × nombre d'exercices + bonus d'ancienneté
        if ($anneesAnciennete >= 1) {
            // Nombre d'exercices = années d'ancienneté - 1 (le premier exercice commence après 1 an)
            $nombreExercices = floor($anneesAnciennete - 1);

            // Jours de base : 30 jours par exercice
            $joursBase = 30 * $nombreExercices;

            // Bonus : +1 jour par année d'ancienneté
            $joursBonus = $anneesAnciennete;

            $joursAcquis = floor($joursBase + $joursBonus);
        } else {
            // Moins d'un an de service = pas de congés acquis
            $nombreExercices = 0;
            $joursBase = 0;
            $joursBonus = 0;
            $joursAcquis = 0;
        }

        // Calculer les jours pris (congés validés dans l'année)
        $joursPris = Conge::where('agent_id', $agent->id)
            ->where('statut', 'valide_drh')
            ->where('type', 'annuel')
            ->whereYear('date_debut', $annee)
            ->sum('nombre_jours');

        $joursRestants = max(0, $joursAcquis - $joursPris);

        return [
            'jours_acquis' => $joursAcquis,
            'jours_pris' => $joursPris,
            'jours_restants' => $joursRestants,
            'annees_anciennete' => $anneesAnciennete,
            'nombre_exercices' => $nombreExercices,
            'jours_base' => $joursBase,
            'jours_bonus' => $joursBonus,
            'formule' => $anneesAnciennete >= 1
                ? "30 jours × {$nombreExercices} exercices + {$joursBonus} jours (bonus ancienneté) = {$joursAcquis} jours"
                : "Moins d'1 an d'ancienneté = 0 jour",
        ];
    }

    public static function mettreAJourSolde(Agent $agent, $annee = null)
    {
        $annee = $annee ?: now()->year;
        $solde = self::calculerSolde($agent, $annee);

        return self::updateOrCreate(
            ['agent_id' => $agent->id, 'annee' => $annee],
            [
                'jours_acquis' => $solde['jours_acquis'],
                'jours_pris' => $solde['jours_pris'],
                'jours_restants' => $solde['jours_restants'],
                'date_calcul' => now(),
            ]
        );
    }

    public function estEligible()
    {
        return $this->jours_acquis > 0;
    }

    public function aSoldeDisponible($nombreJours)
    {
        return $this->jours_restants >= $nombreJours;
    }

    // Méthode pour obtenir le détail du calcul
    public static function getDetailCalcul(Agent $agent, $annee = null)
    {
        $annee = $annee ?: now()->year;
        $solde = self::calculerSolde($agent, $annee);

        return [
            'agent' => $agent->full_name,
            'matricule' => $agent->matricule,
            'date_recrutement' => $agent->date_recrutement->format('d/m/Y'),
            'annees_anciennete' => $solde['annees_anciennete'],
            'nombre_exercices' => $solde['nombre_exercices'],
            'jours_base' => $solde['jours_base'],
            'jours_bonus' => $solde['jours_bonus'],
            'jours_acquis' => $solde['jours_acquis'],
            'jours_pris' => $solde['jours_pris'],
            'jours_restants' => $solde['jours_restants'],
            'eligible' => $solde['jours_acquis'] > 0,
            'formule' => $solde['annees_anciennete'] >= 1
                ? "30 jours × {$solde['nombre_exercices']} exercices + {$solde['jours_bonus']} jours (bonus ancienneté) = {$solde['jours_acquis']} jours"
                : "Moins d'1 an d'ancienneté = 0 jour"
        ];
    }
}
