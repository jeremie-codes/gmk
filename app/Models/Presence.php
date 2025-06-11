<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'date',
        'heure_arrivee',
        'heure_depart',
        'statut',
        'motif',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_arrivee' => 'datetime:H:i',
        'heure_depart' => 'datetime:H:i',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Méthodes utilitaires
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'present' => 'bg-green-100 text-green-800',
            'present_retard' => 'bg-yellow-100 text-yellow-800',
            'justifie' => 'bg-blue-100 text-blue-800',
            'absence_autorisee' => 'bg-purple-100 text-purple-800',
            'absent' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'present' => 'Présent',
            'present_retard' => 'Présent avec retard',
            'justifie' => 'Absence justifiée',
            'absence_autorisee' => 'Absence autorisée',
            'absent' => 'Absent',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'present' => 'bx-check-circle',
            'present_retard' => 'bx-time',
            'justifie' => 'bx-info-circle',
            'absence_autorisee' => 'bx-calendar-check',
            'absent' => 'bx-x-circle',
            default => 'bx-help-circle',
        };
    }
}