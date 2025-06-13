<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valves', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->enum('priorite', ['basse', 'normale', 'haute', 'urgente'])->default('normale');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('actif')->default(true);
            $table->foreignId('publie_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('priorite');
            $table->index('date_debut');
            $table->index('date_fin');
            $table->index('actif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valves');
    }
};
