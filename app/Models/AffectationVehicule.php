<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationVehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_vehicule_id',
        'vehicule_id',
        'chauffeur_id',
        'date_heure_affectation',
        'kilometrage_depart',
        'kilometrage_retour',
        'carburant_depart',
        'carburant_retour',
        'carburant_consomme',
        'observations_depart',
        'observations_retour',
        'etat_vehicule_depart',
        'etat_vehicule_retour',
        'affecte_par',
        'date_retour_effective',
        'retour_confirme',
    ];

    protected $casts = [
        'date_heure_affectation' => 'datetime',
        'date_retour_effective' => 'datetime',
        'carburant_depart' => 'decimal:2',
        'carburant_retour' => 'decimal:2',
        'carburant_consomme' => 'decimal:2',
        'retour_confirme' => 'boolean',
    ];

    // Relations
    public function demandeVehicule()
    {
        return $this->belongsTo(DemandeVehicule::class);
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function affectePar()
    {
        return $this->belongsTo(User::class, 'affecte_par');
    }

    // Méthodes utilitaires
    public function getKilometrageParcouru()
    {
        if (!$this->kilometrage_retour || !$this->kilometrage_depart) {
            return 0;
        }

        return $this->kilometrage_retour - $this->kilometrage_depart;
    }

    public function calculerCarburantConsomme()
    {
        if (!$this->carburant_depart || !$this->carburant_retour) {
            return null;
        }

        return $this->carburant_depart - $this->carburant_retour;
    }

    public function getConsommationAux100()
    {
        $kilometrage = $this->getKilometrageParcouru();

        if ($kilometrage <= 0 || !$this->carburant_consomme) {
            return 0;
        }

        return ($this->carburant_consomme / $kilometrage) * 100;
    }

    public function getDureeEffective()
    {
        if (!$this->date_retour_effective) {
            return null;
        }

        return $this->date_heure_affectation->diffInHours($this->date_retour_effective);
    }

    public function estEnCours()
    {
        return !$this->retour_confirme;
    }

    public function peutConfirmerRetour()
    {
        return !$this->retour_confirme;
    }
}
