<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_valves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('valve_id')->constrained()->onDelete('cascade');
            $table->enum('type_document', ['pdf', 'word', 'excel', 'image', 'autre'])->default('autre');
            $table->string('chemin_fichier');
            $table->unsignedBigInteger('taille_fichier')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('valve_id');
            $table->index('type_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_valves');
    }
};
