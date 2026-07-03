<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('arabetdb.partida', function (Blueprint $table) {
            $table->string('imagem_mandante')->nullable();
            $table->string('imagem_visitante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arabetdb.partida', function (Blueprint $table) {
            $table->dropColumn('imagem_mandante');
            $table->dropColumn('imagem_visitante');
        });
    }
};
