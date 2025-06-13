<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceVehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'type_maintenance',
        'date_maintenance',
        'kilometrage_maintenance',
        'description',
        'garage_atelier',
        'cout',
        'pieces_changees',
        'date_prochaine_maintenance',
        'kilometrage_prochain_entretien',
        'statut',
        'observations',
        'effectue_par',
    ];

    protected $casts = [
        'date_maintenance' => 'date',
        'date_prochaine_maintenance' => 'date',
        'cout' => 'decimal:2',
    ];

    // Relations
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function effectuePar()
    {
        return $this->belongsTo(User::class, 'effectue_par');
    }

    // Méthodes utilitaires pour les badges
    public function getTypeBadgeClass()
    {
        return match($this->type_maintenance) {
            'preventive' => 'bg-blue-100 text-blue-800',
            'corrective' => 'bg-red-100 text-red-800',
            'visite_technique' => 'bg-purple-100 text-purple-800',
            'vidange' => 'bg-green-100 text-green-800',
            'reparation' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabel()
    {
        return match($this->type_maintenance) {
            'preventive' => 'Préventive',
            'corrective' => 'Corrective',
            'visite_technique' => 'Visite technique',
            'vidange' => 'Vidange',
            'reparation' => 'Réparation',
            default => 'Autre',
        };
    }

    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'planifie' => 'bg-yellow-100 text-yellow-800',
            'en_cours' => 'bg-blue-100 text-blue-800',
            'termine' => 'bg-green-100 text-green-800',
            'reporte' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'planifie' => 'Planifié',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'reporte' => 'Reporté',
            default => 'Inconnu',
        };
    }

    // Scopes
    public function scopePlanifie($query)
    {
        return $query->where('statut', 'planifie');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeReporte($query)
    {
        return $query->where('statut', 'reporte');
    }
}