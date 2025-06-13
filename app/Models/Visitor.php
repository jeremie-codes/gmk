<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'motif',
        'direction',
        'destination',
        'heure_arrivee',
        'heure_depart',
        'observations',
        'piece_identite',
        'enregistre_par',
    ];

    protected $casts = [
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
    ];

    // Relations
    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    // Méthodes utilitaires pour les badges
    public function getTypeBadgeClass()
    {
        return match($this->type) {
            'entrepreneur' => 'bg-blue-100 text-blue-800',
            'visiteur' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'entrepreneur' => 'Entrepreneur',
            'visiteur' => 'Visiteur',
            default => 'Inconnu',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'entrepreneur' => 'bx-briefcase',
            'visiteur' => 'bx-user',
            default => 'bx-help-circle',
        };
    }

    // Scopes
    public function scopeEntrepreneur($query)
    {
        return $query->where('type', 'entrepreneur');
    }

    public function scopeVisiteur($query)
    {
        return $query->where('type', 'visiteur');
    }

    public function scopeEnCours($query)
    {
        return $query->whereNull('heure_depart');
    }

    public function scopeTermine($query)
    {
        return $query->whereNotNull('heure_depart');
    }

    // Méthodes utilitaires
    public function estEnCours()
    {
        return is_null($this->heure_depart);
    }

    public function getDureeVisite()
    {
        if (!$this->heure_depart) {
            return null;
        }

        return $this->heure_arrivee->diffInMinutes($this->heure_depart);
    }

    public function getDureeVisiteFormatee()
    {
        $duree = $this->getDureeVisite();

        if (!$duree) {
            return 'En cours';
        }

        $heures = intval($duree / 60);
        $minutes = $duree % 60;

        if ($heures > 0) {
            return "{$heures}h {$minutes}min";
        }

        return "{$minutes}min";
    }
}
