<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->string('marque');
            $table->string('modele');
            $table->string('type_vehicule'); // Berline, 4x4, Utilitaire, etc.
            $table->integer('annee');
            $table->string('couleur');
            $table->string('numero_chassis')->unique();
            $table->string('numero_moteur')->unique();
            $table->integer('nombre_places');
            $table->decimal('kilometrage', 10, 2)->default(0);
            $table->date('date_acquisition');
            $table->decimal('prix_acquisition', 12, 2)->nullable();
            $table->enum('etat', ['bon_etat', 'panne', 'entretien', 'a_declasser'])->default('bon_etat');
            $table->date('date_derniere_visite_technique')->nullable();
            $table->date('date_prochaine_visite_technique')->nullable();
            $table->date('date_derniere_vidange')->nullable();
            $table->integer('kilometrage_derniere_vidange')->nullable();
            $table->text('observations')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('etat');
            $table->index('disponible');
            $table->index('type_vehicule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};