<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->foreignId('demande_fourniture_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement']);
            $table->integer('quantite');
            $table->integer('quantite_avant');
            $table->integer('quantite_apres');
            $table->text('motif');
            $table->foreignId('effectue_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['stock_id', 'type_mouvement']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
