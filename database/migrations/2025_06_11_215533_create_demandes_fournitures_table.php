<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_fournitures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->nullable()->constrained('stocks')->onDelete('set null');
            $table->string('direction');
            $table->string('service');
            $table->text('besoin');
            $table->integer('quantite');
            $table->string('unite')->default('unité'); // unité, kg, litre, etc.
            $table->enum('urgence', ['faible', 'normale', 'elevee', 'critique'])->default('normale');
            $table->enum('statut', ['en_attente', 'approuve', 'en_cours', 'livre', 'rejete'])->default('en_attente');
            $table->date('date_besoin')->nullable(); // Date souhaitée de livraison
            $table->text('justification')->nullable();
            $table->text('commentaire_approbateur')->nullable();
            $table->date('date_approbation')->nullable();
            $table->foreignId('approuve_par')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_livraison')->nullable();
            $table->text('commentaire_livraison')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['direction', 'service']);
            $table->index('statut');
            $table->index('urgence');
            $table->index('date_besoin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_fournitures');
    }
};
