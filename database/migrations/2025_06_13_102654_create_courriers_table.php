<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courriers', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('objet');
            $table->enum('type_courrier', ['entrant', 'sortant', 'interne'])->default('entrant');
            $table->string('expediteur');
            $table->string('destinataire');
            $table->date('date_reception')->nullable();
            $table->date('date_envoi')->nullable();
            $table->date('date_traitement')->nullable();
            $table->enum('statut', ['recu', 'en_cours', 'traite', 'archive', 'annule'])->default('recu');
            $table->enum('priorite', ['basse', 'normale', 'haute'])->default('normale');
            $table->text('description')->nullable();
            $table->string('emplacement_physique')->nullable();
            $table->string('chemin_fichier')->nullable();
            $table->boolean('confidentiel')->default(false);
            $table->foreignId('enregistre_par')->constrained('users')->onDelete('cascade');
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->text('commentaires')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('reference');
            $table->index('type_courrier');
            $table->index('statut');
            $table->index('priorite');
            $table->index('date_reception');
            $table->index('date_traitement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courriers');
    }
};
