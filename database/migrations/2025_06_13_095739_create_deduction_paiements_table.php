<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deduction_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_id')->constrained()->onDelete('cascade');
            $table->string('libelle');
            $table->decimal('montant', 12, 2);
            $table->text('description')->nullable();
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('paiement_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deduction_paiements');
    }
};
