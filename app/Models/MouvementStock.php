<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'demande_fourniture_id',
        'type_mouvement',
        'quantite',
        'quantite_avant',
        'quantite_apres',
        'motif',
        'effectue_par',
    ];

    // Relations
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function demandeFourniture()
    {
        return $this->belongsTo(DemandeFourniture::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'effectue_par');
    }

    // Accesseurs pour les badges
    public function getTypeBadgeClass()
    {
        return match($this->type_mouvement) {
            'entree' => 'bg-green-100 text-green-800',
            'sortie' => 'bg-red-100 text-red-800',
            'ajustement' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabel()
    {
        return match($this->type_mouvement) {
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'ajustement' => 'Ajustement',
            default => 'Inconnu',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type_mouvement) {
            'entree' => 'bx-plus-circle',
            'sortie' => 'bx-minus-circle',
            'ajustement' => 'bx-edit-alt',
            default => 'bx-help-circle',
        };
    }

    // Scopes
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }

    public function scopeAjustements($query)
    {
        return $query->where('type_mouvement', 'ajustement');
    }
}
