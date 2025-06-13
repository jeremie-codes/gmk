<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicule_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('chauffeur_id')->nullable()->constrained()->onDelete('set null');
            $table->string('direction');
            $table->string('service');
            $table->string('destination');
            $table->text('motif');
            $table->datetime('date_heure_sortie');
            $table->datetime('date_heure_retour_prevue');
            $table->datetime('date_heure_retour_effective')->nullable();
            $table->integer('duree_prevue');
            $table->integer('nombre_passagers')->default(1);
            $table->enum('urgence', ['faible', 'normale', 'elevee', 'critique'])->default('normale');
            $table->enum('statut', ['en_attente', 'approuve', 'affecte', 'en_cours', 'termine', 'rejete'])->default('en_attente');
            $table->text('justification')->nullable();
            $table->text('commentaire_approbateur')->nullable();
            $table->date('date_approbation')->nullable();
            $table->foreignId('approuve_par')->nullable()->constrained('users')->onDelete('set null');
            $table->text('commentaire_affectation')->nullable();
            $table->date('date_affectation')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('statut');
            $table->index('urgence');
            $table->index('date_heure_sortie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_vehicules');
    }
};
