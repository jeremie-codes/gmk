<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_article',
        'description',
        'reference',
        'categorie',
        'quantite_stock',
        'quantite_minimum',
        'unite',
        'prix_unitaire',
        'fournisseur',
        'date_derniere_entree',
        'quantite_derniere_entree',
        'emplacement',
        'statut',
    ];

    protected $casts = [
        'date_derniere_entree' => 'date',
        'prix_unitaire' => 'decimal:2',
    ];

    // Relations
    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function demandesFournitures()
    {
        return $this->hasManyThrough(DemandeFourniture::class, MouvementStock::class);
    }

    // Accesseurs pour les badges
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'disponible' => 'bg-green-100 text-green-800',
            'rupture' => 'bg-red-100 text-red-800',
            'alerte' => 'bg-orange-100 text-orange-800',
            'indisponible' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'disponible' => 'Disponible',
            'rupture' => 'Rupture de stock',
            'alerte' => 'Stock faible',
            'indisponible' => 'Indisponible',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'disponible' => 'bx-check-circle',
            'rupture' => 'bx-x-circle',
            'alerte' => 'bx-error-circle',
            'indisponible' => 'bx-minus-circle',
            default => 'bx-help-circle',
        };
    }

    // Scopes
    public function scopeDisponible($query)
    {
        return $query->where('statut', 'disponible');
    }

    public function scopeRupture($query)
    {
        return $query->where('statut', 'rupture');
    }

    public function scopeAlerte($query)
    {
        return $query->where('statut', 'alerte');
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    // Méthodes utilitaires
    public function mettreAJourStatut()
    {
        if ($this->quantite_stock <= 0) {
            $this->statut = 'rupture';
        } elseif ($this->quantite_stock <= $this->quantite_minimum) {
            $this->statut = 'alerte';
        } else {
            $this->statut = 'disponible';
        }

        $this->save();
    }

    public function ajouterStock($quantite, $motif, $userId)
    {
        $quantiteAvant = $this->quantite_stock;
        $this->quantite_stock += $quantite;
        $this->date_derniere_entree = now();
        $this->quantite_derniere_entree = $quantite;
        $this->mettreAJourStatut();

        // Enregistrer le mouvement
        MouvementStock::create([
            'stock_id' => $this->id,
            'type_mouvement' => 'entree',
            'quantite' => $quantite,
            'quantite_avant' => $quantiteAvant,
            'quantite_apres' => $this->quantite_stock,
            'motif' => $motif,
            'effectue_par' => $userId,
        ]);
    }

    public function retirerStock($quantite, $motif, $userId, $demandeId = null)
    {
        if ($this->quantite_stock < $quantite) {
            throw new \Exception('Stock insuffisant');
        }

        $quantiteAvant = $this->quantite_stock;
        $this->quantite_stock -= $quantite;
        $this->mettreAJourStatut();

        // Enregistrer le mouvement
        MouvementStock::create([
            'stock_id' => $this->id,
            'demande_fourniture_id' => $demandeId,
            'type_mouvement' => 'sortie',
            'quantite' => $quantite,
            'quantite_avant' => $quantiteAvant,
            'quantite_apres' => $this->quantite_stock,
            'motif' => $motif,
            'effectue_par' => $userId,
        ]);
    }

    public function getValeurStock()
    {
        return $this->quantite_stock * ($this->prix_unitaire ?? 0);
    }

    public function estEnRupture()
    {
        return $this->quantite_stock <= 0;
    }

    public function estEnAlerte()
    {
        return $this->quantite_stock <= $this->quantite_minimum && $this->quantite_stock > 0;
    }
}
