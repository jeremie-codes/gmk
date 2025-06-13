<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimePaiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'paiement_id',
        'libelle',
        'montant',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    // Relations
    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
