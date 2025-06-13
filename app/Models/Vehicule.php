<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'couleur',
        'type_vehicule',
        'nombre_places',
        'numero_chassis',
        'numero_moteur',
        'date_mise_service',
        'date_acquisition',
        'prix_acquisition',
        'kilometrage',
        'consommation_moyenne',
        'etat',
        'date_derniere_visite_technique',
        'date_prochaine_visite_technique',
        'date_derniere_vidange',
        'kilometrage_derniere_vidange',
        'observations',
        'photo',
        'disponible',
    ];

    protected $casts = [
        'date_mise_service' => 'date',
        'date_acquisition' => 'date',
        'date_derniere_visite_technique' => 'date',
        'date_prochaine_visite_technique' => 'date',
        'date_derniere_vidange' => 'date',
        'consommation_moyenne' => 'decimal:2',
        'prix_acquisition' => 'decimal:2',
        'disponible' => 'boolean',
    ];

    // Relations
    public function demandesVehicules()
    {
        return $this->hasMany(DemandeVehicule::class);
    }

    public function maintenances()
    {
        return $this->hasMany(MaintenanceVehicule::class);
    }

    public function affectations()
    {
        return $this->hasMany(AffectationVehicule::class);
    }

    // Scopes
    public function scopeBonEtat($query)
    {
        return $query->where('etat', 'bon_etat');
    }

    public function scopeEnPanne($query)
    {
        return $query->where('etat', 'panne');
    }

    public function scopeEnEntretien($query)
    {
        return $query->where('etat', 'entretien');
    }

    public function scopeADeclasser($query)
    {
        return $query->where('etat', 'a_declasser');
    }

    public function scopeDisponible($query)
    {
        return $query->where('etat', 'bon_etat')->where('disponible', true);
    }

    // Accesseurs
    public function getEtatBadgeClass()
    {
        return match($this->etat) {
            'bon_etat' => 'bg-green-100 text-green-800',
            'panne' => 'bg-red-100 text-red-800',
            'entretien' => 'bg-yellow-100 text-yellow-800',
            'a_declasser' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getEtatLabel()
    {
        return match($this->etat) {
            'bon_etat' => 'Bon état',
            'panne' => 'En panne',
            'entretien' => 'En entretien',
            'a_declasser' => 'À déclasser',
            default => 'Inconnu',
        };
    }

    public function getEtatIcon()
    {
        return match($this->etat) {
            'bon_etat' => 'bx-check-circle',
            'panne' => 'bx-x-circle',
            'entretien' => 'bx-wrench',
            'a_declasser' => 'bx-trash',
            default => 'bx-help-circle',
        };
    }

    public function getAgeAttribute()
    {
        return $this->annee ? (date('Y') - $this->annee) : null;
    }

    public function getAncienneteServiceAttribute()
    {
        return $this->date_mise_service ? $this->date_mise_service->diffInYears(Carbon::now()) : null;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }

    public function hasPhoto()
    {
        return $this->photo && file_exists(public_path('storage/' . $this->photo));
    }

    // Méthodes utilitaires
    public function estDisponible($dateDebut = null, $dateFin = null)
    {
        if ($this->etat !== 'bon_etat' || !$this->disponible) {
            return false;
        }

        if (!$dateDebut || !$dateFin) {
            return !$this->estEnMission();
        }

        // Vérifier s'il y a des affectations qui chevauchent la période demandée
        return !$this->affectations()
            ->whereHas('demandeVehicule', function($query) {
                $query->whereIn('statut', ['affecte', 'en_cours']);
            })
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_heure_affectation', [$dateDebut, $dateFin])
                      ->orWhere(function($q) use ($dateDebut, $dateFin) {
                          $q->where('date_heure_affectation', '<=', $dateDebut)
                            ->where(function($q2) use ($dateFin) {
                                $q2->whereNull('date_retour_effective')
                                   ->orWhere('date_retour_effective', '>=', $dateFin);
                            });
                      });
            })
            ->exists();
    }

    public function estEnMission()
    {
        return $this->affectations()
            ->whereHas('demandeVehicule', function($query) {
                $query->where('statut', 'en_cours');
            })
            ->where('retour_confirme', false)
            ->exists();
    }

    public function getDerniereMaintenance()
    {
        return $this->maintenances()
            ->orderBy('date_maintenance', 'desc')
            ->first();
    }

    public function getProchaineMaintenance()
    {
        $derniere = $this->getDerniereMaintenance();
        if (!$derniere) {
            return null;
        }

        // Calculer la prochaine maintenance (exemple: tous les 10 000 km ou 6 mois)
        $prochainKm = $derniere->kilometrage_maintenance + 10000;
        $prochaineMaintenance = $derniere->date_maintenance->addMonths(6);

        return [
            'date_prevue' => $prochaineMaintenance,
            'kilometrage_prevu' => $prochainKm,
            'est_due' => $this->kilometrage >= $prochainKm || now() >= $prochaineMaintenance
        ];
    }

    public function needsVisiteTechnique()
    {
        if (!$this->date_prochaine_visite_technique) {
            return true;
        }

        return $this->date_prochaine_visite_technique->isPast();
    }

    public function needsVidange()
    {
        if (!$this->date_derniere_vidange || !$this->kilometrage_derniere_vidange) {
            return true;
        }

        // Vidange tous les 5000 km ou 6 mois
        $kmDiff = $this->kilometrage - $this->kilometrage_derniere_vidange;
        $dateDiff = $this->date_derniere_vidange->diffInMonths(now());

        return $kmDiff >= 5000 || $dateDiff >= 6;
    }

    public function getNombreDemandesEnCours()
    {
        return $this->demandesVehicules()
            ->whereIn('statut', ['en_attente', 'approuve'])
            ->count();
    }

    public function getTauxUtilisation($periode = 30)
    {
        $totalJours = $periode;
        $joursUtilises = $this->affectations()
            ->where('date_heure_affectation', '>=', now()->subDays($periode))
            ->where('retour_confirme', true)
            ->sum(\DB::raw('DATEDIFF(COALESCE(date_retour_effective, NOW()), date_heure_affectation) + 1'));

        return $totalJours > 0 ? round(($joursUtilises / $totalJours) * 100, 2) : 0;
    }

    public function getCoutMaintenanceTotal()
    {
        return $this->maintenances()->sum('cout');
    }

    public function getConsommationMoyenneCalculee()
    {
        // Calculer la consommation moyenne basée sur les missions récentes
        $affectations = $this->affectations()
            ->where('retour_confirme', true)
            ->whereNotNull('carburant_consomme')
            ->whereNotNull('kilometrage_retour')
            ->whereNotNull('kilometrage_depart')
            ->where('kilometrage_retour', '>', 'kilometrage_depart')
            ->get();

        if ($affectations->isEmpty()) {
            return $this->consommation_moyenne ?? 0;
        }

        $totalCarburant = 0;
        $totalDistance = 0;

        foreach ($affectations as $affectation) {
            $distance = $affectation->kilometrage_retour - $affectation->kilometrage_depart;
            if ($distance > 0) {
                $totalDistance += $distance;
                $totalCarburant += $affectation->carburant_consomme ?? 0;
            }
        }

        return $totalDistance > 0 ? round(($totalCarburant / $totalDistance) * 100, 2) : 0;
    }
}
