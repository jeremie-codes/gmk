<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_courriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courrier_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['creation', 'en_cours', 'traite', 'archive', 'annule', 'modification', 'ajout_document']);
            $table->text('commentaire')->nullable();
            $table->foreignId('effectue_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('courrier_id');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_courriers');
    }
};
