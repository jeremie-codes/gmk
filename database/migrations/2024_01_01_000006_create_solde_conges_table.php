<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solde_conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->integer('annee');
            $table->integer('jours_acquis')->default(0); // Calculé automatiquement
            $table->integer('jours_pris')->default(0);   // Calculé automatiquement
            $table->integer('jours_restants')->default(0); // Calculé automatiquement
            $table->date('date_calcul');
            $table->timestamps();
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['agent_id', 'annee']);
            $table->index('annee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solde_conges');
    }
};