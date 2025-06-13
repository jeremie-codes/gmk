<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained()->onDelete('cascade');
            $table->enum('type_maintenance', ['preventive', 'corrective', 'visite_technique', 'vidange', 'reparation']);
            $table->date('date_maintenance');
            $table->integer('kilometrage_maintenance');
            $table->text('description');
            $table->string('garage_atelier')->nullable();
            $table->decimal('cout', 10, 2)->nullable();
            $table->text('pieces_changees')->nullable();
            $table->date('date_prochaine_maintenance')->nullable();
            $table->integer('kilometrage_prochain_entretien')->nullable();
            $table->enum('statut', ['planifie', 'en_cours', 'termine', 'reporte'])->default('planifie');
            $table->text('observations')->nullable();
            $table->foreignId('effectue_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['vehicule_id', 'type_maintenance']);
            $table->index('date_maintenance');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_vehicules');
    }
};