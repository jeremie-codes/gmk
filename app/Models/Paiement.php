<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'type_paiement',
        'montant_brut',
        'montant_net',
        'date_paiement',
        'mois_concerne',
        'annee_concernee',
        'statut',
        'methode_paiement',
        'reference_paiement',
        'commentaire',
        'cree_par',
        'valide_par',
        'date_validation',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'date_validation' => 'date',
        'montant_brut' => 'decimal:2',
        'montant_net' => 'decimal:2',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function deductions()
    {
        return $this->hasMany(DeductionPaiement::class);
    }

    public function primes()
    {
        return $this->hasMany(PrimePaiement::class);
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopePaye($query)
    {
        return $query->where('statut', 'paye');
    }

    public function scopeAnnule($query)
    {
        return $query->where('statut', 'annule');
    }

    public function scopeDuMois($query, $mois, $annee)
    {
        return $query->where('mois_concerne', $mois)
                     ->where('annee_concernee', $annee);
    }

    // Méthodes utilitaires
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'valide' => 'bg-blue-100 text-blue-800',
            'paye' => 'bg-green-100 text-green-800',
            'annule' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'valide' => 'Validé',
            'paye' => 'Payé',
            'annule' => 'Annulé',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'en_attente' => 'bx-time-five',
            'valide' => 'bx-check',
            'paye' => 'bx-check-double',
            'annule' => 'bx-x',
            default => 'bx-help-circle',
        };
    }

    public function getTypePaiementLabel()
    {
        return match($this->type_paiement) {
            'salaire' => 'Salaire',
            'prime' => 'Prime',
            'indemnite' => 'Indemnité',
            'avance' => 'Avance',
            'solde_tout_compte' => 'Solde de tout compte',
            default => 'Autre',
        };
    }

    public function getTypePaiementBadgeClass()
    {
        return match($this->type_paiement) {
            'salaire' => 'bg-blue-100 text-blue-800',
            'prime' => 'bg-green-100 text-green-800',
            'indemnite' => 'bg-purple-100 text-purple-800',
            'avance' => 'bg-orange-100 text-orange-800',
            'solde_tout_compte' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getMethodePaiementLabel()
    {
        return match($this->methode_paiement) {
            'virement' => 'Virement bancaire',
            'cheque' => 'Chèque',
            'especes' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            default => 'Autre',
        };
    }

    public function getMoisConcerneLabel()
    {
        $mois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        return $mois[$this->mois_concerne] ?? 'Inconnu';
    }

    public function getPeriodeLabel()
    {
        return $this->getMoisConcerneLabel() . ' ' . $this->annee_concernee;
    }

    // Calcul du décompte final
    public static function calculerDecompteFinal(Agent $agent)
    {
        // Récupérer le salaire de base de l'agent
        $salaireBase = $agent->salaire_base ?? 0;

        // Calculer l'ancienneté
        $anciennete = $agent->date_recrutement ? $agent->date_recrutement->diffInYears(Carbon::now()) : 0;

        // Prime d'ancienneté (exemple: 1% par année d'ancienneté, plafonné à 15%)
        $tauxAnciennete = min($anciennete, 15) / 100;
        $primeAnciennete = $salaireBase * $tauxAnciennete;

        // Indemnité de congés payés (1/12 du salaire annuel)
        $indemniteCongés = $salaireBase / 12;

        // Indemnité de préavis (1 mois de salaire)
        $indemnitePreavis = $salaireBase;

        // Indemnité de licenciement (selon l'ancienneté)
        $indemniteLicenciement = 0;
        if ($anciennete >= 1) {
            // Exemple: 25% du salaire mensuel par année d'ancienneté
            $indemniteLicenciement = $salaireBase * 0.25 * $anciennete;
        }

        // Total brut
        $totalBrut = $salaireBase + $primeAnciennete + $indemniteCongés + $indemnitePreavis + $indemniteLicenciement;

        // Déductions (exemple: 5% pour charges sociales)
        $deductions = $totalBrut * 0.05;

        // Total net
        $totalNet = $totalBrut - $deductions;

        return [
            'salaire_base' => $salaireBase,
            'anciennete' => $anciennete,
            'prime_anciennete' => $primeAnciennete,
            'indemnite_conges' => $indemniteCongés,
            'indemnite_preavis' => $indemnitePreavis,
            'indemnite_licenciement' => $indemniteLicenciement,
            'total_brut' => $totalBrut,
            'deductions' => $deductions,
            'total_net' => $totalNet,
        ];
    }

    // Calcul du salaire mensuel
    public static function calculerSalaireMensuel(Agent $agent, $mois, $annee)
    {
        // Récupérer le salaire de base de l'agent
        $salaireBase = $agent->salaire_base ?? 0;

        // Calculer l'ancienneté
        $anciennete = $agent->date_recrutement ? $agent->date_recrutement->diffInYears(Carbon::now()) : 0;

        // Prime d'ancienneté (exemple: 1% par année d'ancienneté, plafonné à 15%)
        $tauxAnciennete = min($anciennete, 15) / 100;
        $primeAnciennete = $salaireBase * $tauxAnciennete;

        // Récupérer les présences du mois
        $debut = Carbon::createFromDate($annee, $mois, 1)->startOfMonth();
        $fin = Carbon::createFromDate($annee, $mois, 1)->endOfMonth();

        $presences = Presence::where('agent_id', $agent->id)
            ->whereBetween('date', [$debut, $fin])
            ->get();

        // Nombre de jours ouvrables dans le mois
        $joursOuvrables = 0;
        $current = $debut->copy();
        while ($current <= $fin) {
            if ($current->isWeekday()) {
                $joursOuvrables++;
            }
            $current->addDay();
        }

        // Nombre de jours de présence
        $joursPresence = $presences->whereIn('statut', ['present', 'present_retard'])->count();

        // Nombre de jours d'absence justifiée
        $joursAbsenceJustifiee = $presences->whereIn('statut', ['justifie', 'absence_autorisee'])->count();

        // Nombre de jours d'absence non justifiée
        $joursAbsenceNonJustifiee = $presences->where('statut', 'absent')->count();

        // Calcul du salaire au prorata des présences
        $salaireProrata = $salaireBase;
        if ($joursOuvrables > 0) {
            // Les absences non justifiées sont déduites
            $salaireProrata = $salaireBase * (($joursPresence + $joursAbsenceJustifiee) / $joursOuvrables);
        }

        // Primes diverses (à adapter selon les besoins)
        $primes = [
            'transport' => 25000, // Exemple: prime de transport fixe
            'logement' => 50000,  // Exemple: prime de logement fixe
        ];
        $totalPrimes = array_sum($primes);

        // Déductions (exemple: 5% pour charges sociales)
        $tauxChargesSociales = 0.05;
        $chargesSociales = ($salaireProrata + $primeAnciennete) * $tauxChargesSociales;

        // Autres déductions (à adapter selon les besoins)
        $autresDeductions = [
            'avances' => 0, // À calculer selon les avances du mois
            'impots' => ($salaireProrata + $primeAnciennete + $totalPrimes) * 0.02, // Exemple: 2% d'impôts
        ];
        $totalDeductions = $chargesSociales + array_sum($autresDeductions);

        // Total brut
        $totalBrut = $salaireProrata + $primeAnciennete + $totalPrimes;

        // Total net
        $totalNet = $totalBrut - $totalDeductions;

        return [
            'salaire_base' => $salaireBase,
            'salaire_prorata' => $salaireProrata,
            'jours_ouvrables' => $joursOuvrables,
            'jours_presence' => $joursPresence,
            'jours_absence_justifiee' => $joursAbsenceJustifiee,
            'jours_absence_non_justifiee' => $joursAbsenceNonJustifiee,
            'prime_anciennete' => $primeAnciennete,
            'primes' => $primes,
            'total_primes' => $totalPrimes,
            'charges_sociales' => $chargesSociales,
            'autres_deductions' => $autresDeductions,
            'total_deductions' => $totalDeductions,
            'total_brut' => $totalBrut,
            'total_net' => $totalNet,
        ];
    }
}
