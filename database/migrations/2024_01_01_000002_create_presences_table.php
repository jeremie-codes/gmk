<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            $table->enum('statut', [
                'present', 
                'present_retard', 
                'justifie', 
                'absence_autorisee', 
                'absent'
            ]);
            $table->text('motif')->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index(['agent_id', 'date']);
            $table->index('date');
            $table->index('statut');
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['agent_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};