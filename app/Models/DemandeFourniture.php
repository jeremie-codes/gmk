<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DemandeFourniture extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'article_id',
        'direction',
        'service',
        'besoin',
        'quantite',
        'unite',
        'urgence',
        'statut',
        'date_besoin',
        'justification',
        'commentaire_approbateur',
        'date_approbation',
        'approuve_par',
        'date_livraison',
        'commentaire_livraison',
    ];

    protected $casts = [
        'date_besoin' => 'date',
        'date_approbation' => 'date',
        'date_livraison' => 'date',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function article()
    {
        return $this->belongsTo(Stock::class, 'article_id');
    }

    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuve_par');
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // Accesseurs pour les badges
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'approuve' => 'bg-blue-100 text-blue-800',
            'en_cours' => 'bg-purple-100 text-purple-800',
            'livre' => 'bg-green-100 text-green-800',
            'rejete' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'approuve' => 'Approuvé',
            'en_cours' => 'En cours',
            'livre' => 'Livré',
            'rejete' => 'Rejeté',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'en_attente' => 'bx-time-five',
            'approuve' => 'bx-check',
            'en_cours' => 'bx-loader-alt',
            'livre' => 'bx-check-double',
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

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeLivre($query)
    {
        return $query->where('statut', 'livre');
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

    public function estEnRetard()
    {
        return $this->date_besoin &&
               $this->date_besoin->isPast() &&
               !in_array($this->statut, ['livre', 'rejete']);
    }

    public function getDelaiLivraison()
    {
        if (!$this->date_besoin) {
            return null;
        }

        $now = Carbon::now();
        if ($this->date_besoin->isFuture()) {
            return $this->date_besoin->diffInDays($now) . ' jours restants';
        } else {
            return $this->date_besoin->diffInDays($now) . ' jours de retard';
        }
    }
}
