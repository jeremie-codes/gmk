<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('nom_article');
            $table->text('description')->nullable();
            $table->string('reference')->unique()->nullable();
            $table->string('categorie');
            $table->integer('quantite_stock');
            $table->integer('quantite_minimum')->default(0); // Seuil d'alerte
            $table->string('unite')->default('unité');
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->string('fournisseur')->nullable();
            $table->date('date_derniere_entree')->nullable();
            $table->integer('quantite_derniere_entree')->nullable();
            $table->text('emplacement')->nullable();
            $table->enum('statut', ['disponible', 'rupture', 'alerte', 'indisponible'])->default('disponible');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('categorie');
            $table->index('statut');
            $table->index('quantite_stock');
            $table->index('nom_article');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
