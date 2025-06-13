<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCourrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'courrier_id',
        'nom_document',
        'type_document',
        'chemin_fichier',
        'taille_fichier',
        'ajoute_par',
        'description',
    ];

    // Relations
    public function courrier()
    {
        return $this->belongsTo(Courrier::class);
    }

    public function ajoutePar()
    {
        return $this->belongsTo(User::class, 'ajoute_par');
    }

    // Méthodes utilitaires
    public function getTypeBadgeClass()
    {
        return match($this->type_document) {
            'pdf' => 'bg-red-100 text-red-800',
            'word' => 'bg-blue-100 text-blue-800',
            'excel' => 'bg-green-100 text-green-800',
            'image' => 'bg-purple-100 text-purple-800',
            'autre' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type_document) {
            'pdf' => 'bx-file-pdf',
            'word' => 'bx-file-doc',
            'excel' => 'bx-file-spreadsheet',
            'image' => 'bx-image',
            'autre' => 'bx-file',
            default => 'bx-file',
        };
    }

    public function getTailleFormatee()
    {
        $taille = $this->taille_fichier;

        if ($taille < 1024) {
            return $taille . ' o';
        } elseif ($taille < 1024 * 1024) {
            return round($taille / 1024, 2) . ' Ko';
        } else {
            return round($taille / (1024 * 1024), 2) . ' Mo';
        }
    }

    // Détecter le type de document à partir de l'extension
    public static function detecterType($extension)
    {
        return match(strtolower($extension)) {
            'pdf' => 'pdf',
            'doc', 'docx' => 'word',
            'xls', 'xlsx', 'csv' => 'excel',
            'jpg', 'jpeg', 'png', 'gif', 'bmp' => 'image',
            default => 'autre',
        };
    }
}
