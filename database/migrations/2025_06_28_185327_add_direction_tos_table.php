<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Ajouter les clés étrangères pour direction et service
            $table->foreignId('direction_id')->nullable()->after('service')->constrained()->onDelete('set null');
            $table->foreignId('service_id')->nullable()->after('direction_id')->constrained()->onDelete('set null');

            // Index pour améliorer les performances
            $table->index('direction_id');
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign(['direction_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn(['direction_id', 'service_id']);
        });
    }
};
