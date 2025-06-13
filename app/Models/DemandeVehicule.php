<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeVehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'vehicule_id',
        'chauffeur_id',
        'direction',
        'service',
        'destination',
        'motif',
        'date_heure_sortie',
        'date_heure_retour_prevue',
        'date_heure_retour_effective',
        'duree_prevue',
        'nombre_passagers',
        'urgence',
        'statut',
        'justification',
        'commentaire_approbateur',
        'date_approbation',
        'approuve_par',
        'commentaire_affectation',
        'date_affectation',
    ];

    protected $casts = [
        'date_heure_sortie' => 'datetime',
        'date_heure_retour_prevue' => 'datetime',
        'date_heure_retour_effective' => 'datetime',
        'date_approbation' => 'date',
        'date_affectation' => 'date',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuve_par');
    }

    public function affectation()
    {
        return $this->hasOne(AffectationVehicule::class);
    }

    // Méthodes utilitaires pour les badges
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'approuve' => 'bg-blue-100 text-blue-800',
            'affecte' => 'bg-purple-100 text-purple-800',
            'en_cours' => 'bg-indigo-100 text-indigo-800',
            'termine' => 'bg-green-100 text-green-800',
            'rejete' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'approuve' => 'Approuvé',
            'affecte' => 'Affecté',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'rejete' => 'Rejeté',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'en_attente' => 'bx-time-five',
            'approuve' => 'bx-check',
            'affecte' => 'bx-car',
            'en_cours' => 'bx-loader-alt',
            'termine' => 'bx-check-double',
            'rejete' => 'bx-x',
            default => 'bx-help-circle',
        };
    }

    public function getUrgenceBadgeClass()
    {
        return match($this->urgence) {
            'faible' => 'bg-gray-100 text-gray-800',
            'normale' => 'bg-blue-100 text-blue-800',
            'elevee' => 'bg-orange-100 text-orange-800',
            'critique' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getUrgenceLabel()
    {
        return match($this->urgence) {
            'faible' => 'Faible',
            'normale' => 'Normale',
            'elevee' => 'Élevée',
            'critique' => 'Critique',
            default => 'Normale',
        };
    }

    public function getUrgenceIcon()
    {
        return match($this->urgence) {
            'faible' => 'bx-down-arrow',
            'normale' => 'bx-minus',
            'elevee' => 'bx-up-arrow',
            'critique' => 'bx-error',
            default => 'bx-minus',
        };
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeApprouve($query)
    {
        return $query->where('statut', 'approuve');
    }

    public function scopeAffecte($query)
    {
        return $query->where('statut', 'affecte');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgence', ['elevee', 'critique']);
    }

    // Méthodes utilitaires
    public function peutEtreModifie()
    {
        return $this->statut === 'en_attente';
    }

    public function peutEtreApprouve()
    {
        return $this->statut === 'en_attente';
    }

    public function peutEtreAffecte()
    {
        return $this->statut === 'approuve';
    }

    public function estEnRetard()
    {
        return $this->date_heure_sortie->isPast() &&
               in_array($this->statut, ['en_attente', 'approuve']);
    }

    public function getDureePrevu()
    {
        return $this->date_heure_sortie->diffInHours($this->date_heure_retour_prevue);
    }

    public function getDureeEffective()
    {
        if (!$this->date_heure_retour_effective) {
            return null;
        }

        return $this->date_heure_sortie->diffInHours($this->date_heure_retour_effective);
    }
}
