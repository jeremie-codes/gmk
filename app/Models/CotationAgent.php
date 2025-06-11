<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CotationAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'periode_debut',
        'periode_fin',
        'nombre_jours_travailles',
        'nombre_presences',
        'nombre_retards',
        'nombre_absences',
        'score_assiduite',
        'score_ponctualite',
        'score_respect_horaire',
        'score_global',
        'mention',
        'observations',
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin' => 'date',
        'score_assiduite' => 'decimal:2',
        'score_ponctualite' => 'decimal:2',
        'score_respect_horaire' => 'decimal:2',
        'score_global' => 'decimal:2',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Méthodes de calcul
    public static function calculerCotation(Agent $agent, $dateDebut, $dateFin)
    {
        $debut = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);

        // Calculer le nombre de jours ouvrables dans la période
        $joursOuvrables = 0;
        $current = $debut->copy();
        while ($current->lte($fin)) {
            if ($current->isWeekday()) {
                $joursOuvrables++;
            }
            $current->addDay();
        }

        // Récupérer les présences de l'agent pour la période
        $presences = Presence::where('agent_id', $agent->id)
            ->whereBetween('date', [$debut, $fin])
            ->get();

        $nombrePresences = $presences->whereIn('statut', ['present', 'present_retard'])->count();
        $nombreRetards = $presences->where('statut', 'present_retard')->count();
        $nombreAbsences = $joursOuvrables - $nombrePresences;

        // Calculer les scores
        $scoreAssiduite = $joursOuvrables > 0 ? ($nombrePresences / $joursOuvrables) * 100 : 0;

        // Score ponctualité : présents à l'heure / total présences
        $scoreponctualite = $nombrePresences > 0 ? (($nombrePresences - $nombreRetards) / $nombrePresences) * 100 : 0;

        // Score respect horaire : présences avec horaires complets (8h-16h)
        $presencesCompletes = $presences->filter(function ($presence) {
            return $presence->heure_arrivee && $presence->heure_depart &&
                   $presence->heure_arrivee->format('H:i') <= '08:00' &&
                   $presence->heure_depart->format('H:i') >= '16:00';
        })->count();

        $scoreRespectHoraire = $nombrePresences > 0 ? ($presencesCompletes / $nombrePresences) * 100 : 0;

        // Score global (moyenne pondérée)
        $scoreGlobal = ($scoreAssiduite * 0.4) + ($scoreponctualite * 0.3) + ($scoreRespectHoraire * 0.3);

        // Déterminer la mention
        $mention = self::determinerMention($scoreGlobal);

        return [
            'nombre_jours_travailles' => $joursOuvrables,
            'nombre_presences' => $nombrePresences,
            'nombre_retards' => $nombreRetards,
            'nombre_absences' => $nombreAbsences,
            'score_assiduite' => round($scoreAssiduite, 2),
            'score_ponctualite' => round($scoreponctualite, 2),
            'score_respect_horaire' => round($scoreRespectHoraire, 2),
            'score_global' => round($scoreGlobal, 2),
            'mention' => $mention,
        ];
    }

    public static function determinerMention($score)
    {
        if ($score >= 80) return 'Élite';
        if ($score >= 70) return 'Très bien';
        if ($score >= 60) return 'Bien';
        if ($score >= 50) return 'Assez-bien';
        return 'Médiocre';
    }

    public function getMentionBadgeClass()
    {
        return match($this->mention) {
            'Élite' => 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white',
            'Très bien' => 'bg-gradient-to-r from-green-500 to-emerald-600 text-white',
            'Bien' => 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white',
            'Assez-bien' => 'bg-gradient-to-r from-orange-400 to-orange-600 text-white',
            'Médiocre' => 'bg-gradient-to-r from-red-500 to-rose-600 text-white',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getMentionIcon()
    {
        return match($this->mention) {
            'Élite' => 'bx-crown',
            'Très bien' => 'bx-medal',
            'Bien' => 'bx-trophy',
            'Assez-bien' => 'bx-star',
            'Médiocre' => 'bx-error-circle',
            default => 'bx-help-circle',
        };
    }

    // Méthode pour enregistrer ou mettre à jour une cotation
    public static function enregistrerCotation(Agent $agent, $dateDebut, $dateFin, $observations = null)
    {
        $calcul = self::calculerCotation($agent, $dateDebut, $dateFin);

        return self::updateOrCreate(
            [
                'agent_id' => $agent->id,
                'periode_debut' => $dateDebut,
                'periode_fin' => $dateFin,
            ],
            array_merge($calcul, [
                'observations' => $observations,
            ])
        );
    }

    // Scopes
    public function scopeParMention($query, $mention)
    {
        return $query->where('mention', $mention);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('periode_debut', [$dateDebut, $dateFin]);
    }
}
