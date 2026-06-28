<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabela Usuario
        Schema::create('arabetdb.usuario', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('nome', 100);
            $table->string('cpf', 11)->unique();
            $table->string('email', 100)->unique();
            $table->string('senha', 255);
            $table->string('tipo', 20);
        });
        DB::statement("ALTER TABLE arabetdb.usuario ADD CONSTRAINT chk_tipo CHECK (tipo IN ('ADMINISTRADOR', 'APOSTADOR'))");

        // 2. Tabela Apostador
        Schema::create('arabetdb.apostador', function (Blueprint $table) {
            $table->integer('id_usuario')->primary();
            $table->date('data_nascimento');
            $table->decimal('saldo', 10, 2)->default(0.00);

            $table->foreign('id_usuario')->references('id_usuario')->on('arabetdb.usuario')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE arabetdb.apostador ADD CONSTRAINT chk_saldo CHECK (saldo >= 0)");
        DB::statement("ALTER TABLE arabetdb.apostador ADD CONSTRAINT chk_idade CHECK (data_nascimento <= CURRENT_DATE - INTERVAL '18 years')");

        // 3. Tabela Time Futebol
        Schema::create('arabetdb.time_futebol', function (Blueprint $table) {
            $table->increments('id_time');
            $table->string('nome', 50)->unique();
            $table->string('cidade', 50);
            $table->string('estadio', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arabetdb.time_futebol');
        Schema::dropIfExists('arabetdb.apostador');
        Schema::dropIfExists('arabetdb.usuario');
    }
};