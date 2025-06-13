<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type', ['entrepreneur', 'visiteur'])->default('visiteur');
            $table->string('motif');
            $table->string('direction');
            $table->string('destination');
            $table->datetime('heure_arrivee');
            $table->datetime('heure_depart')->nullable();
            $table->text('observations')->nullable();
            $table->string('piece_identite')->nullable(); // Champ optionnel pour la pièce d'identité
            $table->foreignId('enregistre_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('type');
            $table->index('direction');
            $table->index('heure_arrivee');
            $table->index('enregistre_par');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
