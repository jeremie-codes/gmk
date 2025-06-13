<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviCourrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'courrier_id',
        'action',
        'commentaire',
        'effectue_par',
    ];

    // Relations
    public function courrier()
    {
        return $this->belongsTo(Courrier::class);
    }

    public function effectuePar()
    {
        return $this->belongsTo(User::class, 'effectue_par');
    }

    // Méthodes utilitaires
    public function getActionBadgeClass()
    {
        return match($this->action) {
            'creation' => 'bg-blue-100 text-blue-800',
            'en_cours' => 'bg-yellow-100 text-yellow-800',
            'traite' => 'bg-green-100 text-green-800',
            'archive' => 'bg-gray-100 text-gray-800',
            'annule' => 'bg-red-100 text-red-800',
            'modification' => 'bg-purple-100 text-purple-800',
            'ajout_document' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getActionLabel()
    {
        return match($this->action) {
            'creation' => 'Création',
            'en_cours' => 'Mise en traitement',
            'traite' => 'Traité',
            'archive' => 'Archivé',
            'annule' => 'Annulé',
            'modification' => 'Modification',
            'ajout_document' => 'Ajout de document',
            default => 'Action inconnue',
        };
    }

    public function getActionIcon()
    {
        return match($this->action) {
            'creation' => 'bx-plus-circle',
            'en_cours' => 'bx-time-five',
            'traite' => 'bx-check',
            'archive' => 'bx-archive',
            'annule' => 'bx-x',
            'modification' => 'bx-edit',
            'ajout_document' => 'bx-file-plus',
            default => 'bx-help-circle',
        };
    }
}
