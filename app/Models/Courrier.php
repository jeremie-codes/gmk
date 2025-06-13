<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Courrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'objet',
        'type_courrier',
        'expediteur',
        'destinataire',
        'date_reception',
        'date_envoi',
        'date_traitement',
        'statut',
        'priorite',
        'description',
        'emplacement_physique',
        'chemin_fichier',
        'confidentiel',
        'enregistre_par',
        'traite_par',
        'commentaires',
    ];

    protected $casts = [
        'date_reception' => 'date',
        'date_envoi' => 'date',
        'date_traitement' => 'date',
        'confidentiel' => 'boolean',
    ];

    // Relations
    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function documents()
    {
        return $this->hasMany(DocumentCourrier::class);
    }

    public function suivis()
    {
        return $this->hasMany(SuiviCourrier::class);
    }

    // Scopes
    public function scopeEntrant($query)
    {
        return $query->where('type_courrier', 'entrant');
    }

    public function scopeSortant($query)
    {
        return $query->where('type_courrier', 'sortant');
    }

    public function scopeInterne($query)
    {
        return $query->where('type_courrier', 'interne');
    }

    public function scopeNonTraite($query)
    {
        return $query->whereNull('date_traitement');
    }

    public function scopeTraite($query)
    {
        return $query->whereNotNull('date_traitement');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priorite', 'haute');
    }

    // Méthodes utilitaires
    public function getStatutBadgeClass()
    {
        return match($this->statut) {
            'recu' => 'bg-blue-100 text-blue-800',
            'en_cours' => 'bg-yellow-100 text-yellow-800',
            'traite' => 'bg-green-100 text-green-800',
            'archive' => 'bg-gray-100 text-gray-800',
            'annule' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabel()
    {
        return match($this->statut) {
            'recu' => 'Reçu',
            'en_cours' => 'En cours',
            'traite' => 'Traité',
            'archive' => 'Archivé',
            'annule' => 'Annulé',
            default => 'Inconnu',
        };
    }

    public function getStatutIcon()
    {
        return match($this->statut) {
            'recu' => 'bx-envelope',
            'en_cours' => 'bx-time-five',
            'traite' => 'bx-check',
            'archive' => 'bx-archive',
            'annule' => 'bx-x',
            default => 'bx-help-circle',
        };
    }

    public function getTypeBadgeClass()
    {
        return match($this->type_courrier) {
            'entrant' => 'bg-green-100 text-green-800',
            'sortant' => 'bg-purple-100 text-purple-800',
            'interne' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabel()
    {
        return match($this->type_courrier) {
            'entrant' => 'Entrant',
            'sortant' => 'Sortant',
            'interne' => 'Interne',
            default => 'Inconnu',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type_courrier) {
            'entrant' => 'bx-log-in',
            'sortant' => 'bx-log-out',
            'interne' => 'bx-transfer',
            default => 'bx-help-circle',
        };
    }

    public function getPrioriteBadgeClass()
    {
        return match($this->priorite) {
            'basse' => 'bg-green-100 text-green-800',
            'normale' => 'bg-blue-100 text-blue-800',
            'haute' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPrioriteLabel()
    {
        return match($this->priorite) {
            'basse' => 'Basse',
            'normale' => 'Normale',
            'haute' => 'Haute',
            default => 'Inconnue',
        };
    }

    public function getPrioriteIcon()
    {
        return match($this->priorite) {
            'basse' => 'bx-down-arrow',
            'normale' => 'bx-right-arrow',
            'haute' => 'bx-up-arrow',
            default => 'bx-help-circle',
        };
    }

    // Méthodes de traitement
    public function marquerEnCours($commentaire = null)
    {
        $this->update([
            'statut' => 'en_cours',
        ]);

        // Ajouter une entrée dans le suivi
        $this->suivis()->create([
            'action' => 'en_cours',
            'commentaire' => $commentaire,
            'effectue_par' => Auth::id(),
        ]);

        return $this;
    }

    public function marquerTraite($commentaire = null)
    {
        $this->update([
            'statut' => 'traite',
            'date_traitement' => now(),
            'traite_par' => Auth::id(),
        ]);

        // Ajouter une entrée dans le suivi
        $this->suivis()->create([
            'action' => 'traite',
            'commentaire' => $commentaire,
            'effectue_par' => Auth::id(),
        ]);

        return $this;
    }

    public function marquerArchive($commentaire = null)
    {
        $this->update([
            'statut' => 'archive',
        ]);

        // Ajouter une entrée dans le suivi
        $this->suivis()->create([
            'action' => 'archive',
            'commentaire' => $commentaire,
            'effectue_par' => Auth::id(),
        ]);

        return $this;
    }

    public function marquerAnnule($commentaire = null)
    {
        $this->update([
            'statut' => 'annule',
        ]);

        // Ajouter une entrée dans le suivi
        $this->suivis()->create([
            'action' => 'annule',
            'commentaire' => $commentaire,
            'effectue_par' => Auth::id(),
        ]);

        return $this;
    }

    // Génération de référence automatique
    public static function genererReference($type)
    {
        $prefix = match($type) {
            'entrant' => 'ENT',
            'sortant' => 'SOR',
            'interne' => 'INT',
            default => 'COU',
        };

        $annee = date('Y');
        $mois = date('m');

        // Compter le nombre de courriers de ce type pour ce mois
        $count = self::where('type_courrier', $type)
                    ->whereYear('created_at', $annee)
                    ->whereMonth('created_at', $mois)
                    ->count() + 1;

        // Formater le numéro séquentiel sur 4 chiffres
        $numero = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$annee}{$mois}-{$numero}";
    }

    // Vérifier si le courrier peut être modifié
    public function peutEtreModifie()
    {
        return in_array($this->statut, ['recu', 'en_cours']);
    }

    // Vérifier si le courrier peut être traité
    public function peutEtreTraite()
    {
        return in_array($this->statut, ['recu', 'en_cours']);
    }

    // Vérifier si le courrier peut être archivé
    public function peutEtreArchive()
    {
        return $this->statut === 'traite';
    }

    // Vérifier si le courrier peut être annulé
    public function peutEtreAnnule()
    {
        return in_array($this->statut, ['recu', 'en_cours']);
    }

    // Vérifier si le courrier est en retard
    public function estEnRetard()
    {
        if ($this->statut === 'traite' || $this->statut === 'archive' || $this->statut === 'annule') {
            return false;
        }

        if ($this->priorite === 'haute' && $this->date_reception) {
            // Pour les courriers urgents, considérer en retard après 2 jours
            return $this->date_reception->diffInDays(now()) > 2;
        } elseif ($this->priorite === 'normale' && $this->date_reception) {
            // Pour les courriers normaux, considérer en retard après 5 jours
            return $this->date_reception->diffInDays(now()) > 5;
        }

        return false;
    }
}
