<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->decimal('salaire_base', 12, 2)->nullable()->after('service');
            $table->string('compte_bancaire')->nullable()->after('adresse');
            $table->string('banque')->nullable()->after('compte_bancaire');
            $table->string('numero_cnps')->nullable()->after('banque');
            $table->string('numero_impots')->nullable()->after('numero_cnps');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn([
                'salaire_base',
                'compte_bancaire',
                'banque',
                'numero_cnps',
                'numero_impots',
            ]);
        });
    }
};
