<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer l'ancienne colonne role si elle existe
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            
            // Ajouter la nouvelle relation avec la table roles
            $table->foreignId('role_id')->nullable()->after('email')->constrained('roles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            
            // Remettre l'ancienne colonne role
            $table->string('role')->default('user')->after('email');
        });
    }
};