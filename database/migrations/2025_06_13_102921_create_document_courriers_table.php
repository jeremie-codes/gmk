<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_courriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courrier_id')->constrained()->onDelete('cascade');
            $table->string('nom_document');
            $table->enum('type_document', ['pdf', 'word', 'excel', 'image', 'autre'])->default('autre');
            $table->string('chemin_fichier');
            $table->unsignedBigInteger('taille_fichier')->nullable();
            $table->foreignId('ajoute_par')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('courrier_id');
            $table->index('type_document');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_courriers');
    }
};
