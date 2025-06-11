<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotation_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->integer('nombre_jours_travailles');
            $table->integer('nombre_presences');
            $table->integer('nombre_retards');
            $table->integer('nombre_absences');
            $table->decimal('score_assiduite', 5, 2);
            $table->decimal('score_ponctualite', 5, 2);
            $table->decimal('score_respect_horaire', 5, 2);
            $table->decimal('score_global', 5, 2);
            $table->enum('mention', ['Élite', 'Très bien', 'Bien', 'Assez-bien', 'Médiocre']);
            $table->text('observations')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['agent_id', 'periode_debut', 'periode_fin']);
            $table->index('mention');
            $table->index('score_global');

            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['agent_id', 'periode_debut', 'periode_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotation_agents');
    }
};
