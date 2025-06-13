<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectation_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_vehicule_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicule_id')->constrained()->onDelete('cascade');
            $table->foreignId('chauffeur_id')->constrained()->onDelete('cascade');
            $table->datetime('date_heure_affectation');
            $table->integer('kilometrage_depart');
            $table->integer('kilometrage_retour')->nullable();
            $table->decimal('carburant_depart', 5, 2)->nullable(); // Niveau en litres
            $table->decimal('carburant_retour', 5, 2)->nullable();
            $table->decimal('carburant_consomme', 8, 2)->nullable();
            $table->text('observations_depart')->nullable();
            $table->text('observations_retour')->nullable();
            $table->enum('etat_vehicule_depart', ['bon_etat', 'panne', 'entretien', 'a_declasser'])->default('bon_etat');
            $table->enum('etat_vehicule_retour', ['bon_etat', 'panne', 'entretien', 'a_declasser'])->nullable();
            $table->foreignId('affecte_par')->constrained('users')->onDelete('cascade');
            $table->datetime('date_retour_effective')->nullable();
            $table->boolean('retour_confirme')->default(false);
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('date_heure_affectation');
            $table->index('retour_confirme');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectation_vehicules');
    }
};