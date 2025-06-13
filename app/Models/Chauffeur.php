<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chauffeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'numero_permis',
        'categorie_permis',
        'date_obtention_permis',
        'date_expiration_permis',
        'experience_annees',
        'statut',
        'observations',
        'disponible',
    ];

    protected $casts = [
        'date_obtention_permis' => 'date',
        'date_expiration_permis' => 'date',
        'disponible' => 'boolean',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function demandesVehicules()
    {
        return $this->hasMany(DemandeVehicule::class);
    }

    public function affectations()
    {
        return $this->hasMany(AffectationVehicule::class);
    }

    public function affectationEnCours()
    {
        return $this->hasOne(AffectationVehicule::class)->where('retour_confirme', false);
    }

    // Accesseurs
    public function getFullNameAttribute()
    {
        return $this->agent->full_name;
    }

    // Méthodes utilitaires pour les badges
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'actif' => 'bg-green-100 text-green-800',
            'suspendu' => 'bg-red-100 text-red-800',
            'inactif' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'actif' => 'Actif',
            'suspendu' => 'Suspendu',
            'inactif' => 'Inactif',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'actif' => 'bx-check-circle',
            'suspendu' => 'bx-pause-circle',
            'inactif' => 'bx-x-circle',
            default => 'bx-help-circle',
        };
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeDisponible($query)
    {
        return $query->where('disponible', true)->where('statut', 'actif');
    }

    // Méthodes utilitaires
    public function estDisponible()
    {
        return $this->disponible && $this->statut === 'actif' && !$this->affectationEnCours;
    }

    public function permisExpireSoon()
    {
        return $this->date_expiration_permis && 
               $this->date_expiration_permis->diffInDays(now()) <= 30;
    }

    public function permisExpire()
    {
        return $this->date_expiration_permis && 
               $this->date_expiration_permis->isPast();
    }

    public function getNombreMissions()
    {
        return $this->affectations()->where('retour_confirme', true)->count();
    }

    public function getKilometrageTotal()
    {
        return $this->affectations()
            ->whereNotNull('kilometrage_retour')
            ->sum(\DB::raw('kilometrage_retour - kilometrage_depart'));
    }
}