<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Valve extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'priorite',
        'date_debut',
        'date_fin',
        'actif',
        'publie_par',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    // Relations
    public function publiePar()
    {
        return $this->belongsTo(User::class, 'publie_par');
    }

    // Méthodes utilitaires pour les badges
    public function getPrioriteBadgeClass()
    {
        return match($this->priorite) {
            'basse' => 'bg-gray-100 text-gray-800',
            'normale' => 'bg-blue-100 text-blue-800',
            'haute' => 'bg-orange-100 text-orange-800',
            'urgente' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPrioriteLabel()
    {
        return match($this->priorite) {
            'basse' => 'Basse',
            'normale' => 'Normale',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
            default => 'Normale',
        };
    }

    public function getPrioriteIcon()
    {
        return match($this->priorite) {
            'basse' => 'bx-down-arrow',
            'normale' => 'bx-right-arrow',
            'haute' => 'bx-up-arrow',
            'urgente' => 'bx-error',
            default => 'bx-right-arrow',
        };
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeEnCours($query)
    {
        $today = Carbon::today();
        return $query->where('actif', true)
                    ->where('date_debut', '<=', $today)
                    ->where(function($q) use ($today) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', $today);
                    });
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    // Méthodes utilitaires
    public function estEnCours()
    {
        $today = Carbon::today();
        return $this->actif &&
               $this->date_debut->lte($today) &&
               ($this->date_fin === null || $this->date_fin->gte($today));
    }

    public function estExpire()
    {
        return $this->date_fin && $this->date_fin->lt(Carbon::today());
    }

    public function getStatutBadgeClass()
    {
        if (!$this->actif) {
            return 'bg-gray-100 text-gray-800';
        }

        if ($this->estExpire()) {
            return 'bg-red-100 text-red-800';
        }

        if ($this->estEnCours()) {
            return 'bg-green-100 text-green-800';
        }

        if ($this->date_debut->gt(Carbon::today())) {
            return 'bg-yellow-100 text-yellow-800';
        }

        return 'bg-gray-100 text-gray-800';
    }

    public function getStatutLabel()
    {
        if (!$this->actif) {
            return 'Inactif';
        }

        if ($this->estExpire()) {
            return 'Expiré';
        }

        if ($this->estEnCours()) {
            return 'En cours';
        }

        if ($this->date_debut->gt(Carbon::today())) {
            return 'À venir';
        }

        return 'Inconnu';
    }

    public function getStatutIcon()
    {
        if (!$this->actif) {
            return 'bx-x-circle';
        }

        if ($this->estExpire()) {
            return 'bx-time-five';
        }

        if ($this->estEnCours()) {
            return 'bx-check-circle';
        }

        if ($this->date_debut->gt(Carbon::today())) {
            return 'bx-calendar';
        }

        return 'bx-help-circle';
    }

    public function getDateRangeFormatted()
    {
        if ($this->date_fin) {
            return "Du {$this->date_debut->format('d/m/Y')} au {$this->date_fin->format('d/m/Y')}";
        }

        return "À partir du {$this->date_debut->format('d/m/Y')}";
    }
}
