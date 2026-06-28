<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabela Partida
        Schema::create('arabetdb.partida', function (Blueprint $table) {
            $table->increments('id_partida');
            $table->integer('id_mandante');
            $table->integer('id_visitante');
            $table->timestamp('data_hora');
            $table->string('status', 20)->default('AGENDADA');
            $table->decimal('odd_mandante', 5, 2);
            $table->decimal('odd_empate', 5, 2);
            $table->decimal('odd_visitante', 5, 2);
            $table->integer('placar_mandante')->default(0);
            $table->integer('placar_visitante')->default(0);

            $table->foreign('id_mandante')->references('id_time')->on('arabetdb.time_futebol');
            $table->foreign('id_visitante')->references('id_time')->on('arabetdb.time_futebol');
        });
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_status CHECK (status IN ('AGENDADA', 'EM_ANDAMENTO', 'FINALIZADA'))");
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_odd_mandante CHECK (odd_mandante > 1)");
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_odd_empate CHECK (odd_empate > 1)");
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_odd_visitante CHECK (odd_visitante > 1)");
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_placar_mandante CHECK (placar_mandante >= 0)");
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_placar_visitante CHECK (placar_visitante >= 0)");
        DB::statement("ALTER TABLE arabetdb.partida ADD CONSTRAINT chk_times_diferentes CHECK (id_mandante <> id_visitante)");

        // 2. Tabela Aposta
        Schema::create('arabetdb.aposta', function (Blueprint $table) {
            $table->increments('id_aposta');
            $table->integer('id_apostador');
            $table->integer('id_partida');
            $table->decimal('valor', 10, 2);
            $table->string('palpite', 10);
            $table->decimal('odd_momento', 5, 2);
            $table->string('status', 20)->default('PENDENTE');
            $table->timestamp('data_aposta')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('id_apostador')->references('id_usuario')->on('arabetdb.apostador');
            $table->foreign('id_partida')->references('id_partida')->on('arabetdb.partida');
        });
        DB::statement("ALTER TABLE arabetdb.aposta ADD CONSTRAINT chk_valor CHECK (valor > 0)");
        DB::statement("ALTER TABLE arabetdb.aposta ADD CONSTRAINT chk_palpite CHECK (palpite IN ('MANDANTE', 'EMPATE', 'VISITANTE'))");
        DB::statement("ALTER TABLE arabetdb.aposta ADD CONSTRAINT chk_status_aposta CHECK (status IN ('PENDENTE', 'GANHA', 'PERDIDA', 'CANCELADA'))");

        // 3. Tabela Transação
        Schema::create('arabetdb.transacao', function (Blueprint $table) {
            $table->increments('id_transacao');
            $table->integer('id_apostador');
            $table->string('tipo', 10);
            $table->decimal('valor', 10, 2);
            $table->timestamp('data_hora')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('id_apostador')->references('id_usuario')->on('arabetdb.apostador');
        });
        DB::statement("ALTER TABLE arabetdb.transacao ADD CONSTRAINT chk_tipo_transacao CHECK (tipo IN ('DEPOSITO', 'SAQUE'))");
        DB::statement("ALTER TABLE arabetdb.transacao ADD CONSTRAINT chk_valor_transacao CHECK (valor > 0)");
    }

    public function down(): void
    {
        Schema::dropIfExists('arabetdb.transacao');
        Schema::dropIfExists('arabetdb.aposta');
        Schema::dropIfExists('arabetdb.partida');
    }
};