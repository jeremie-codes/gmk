<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->enum('type_paiement', ['salaire', 'prime', 'indemnite', 'avance', 'solde_tout_compte', 'autre'])->default('salaire');
            $table->decimal('montant_brut', 12, 2);
            $table->decimal('montant_net', 12, 2);
            $table->date('date_paiement');
            $table->integer('mois_concerne');
            $table->integer('annee_concernee');
            $table->enum('statut', ['en_attente', 'valide', 'paye', 'annule'])->default('en_attente');
            $table->enum('methode_paiement', ['virement', 'cheque', 'especes', 'mobile_money', 'autre'])->nullable();
            $table->string('reference_paiement')->nullable();
            $table->text('commentaire')->nullable();
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->foreignId('valide_par')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_validation')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['agent_id', 'mois_concerne', 'annee_concernee']);
            $table->index('statut');
            $table->index('type_paiement');
            $table->index('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
