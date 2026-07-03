<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS arabetdb.vw_painel_jogos");
        DB::statement("
            CREATE VIEW arabetdb.vw_painel_jogos AS
            SELECT p.id_partida, tm.nome AS mandante, tv.nome AS visitante, p.data_hora,
                   p.odd_mandante, p.odd_empate, p.odd_visitante, p.status,
                   p.imagem_mandante, p.imagem_visitante
            FROM arabetdb.partida p
            INNER JOIN arabetdb.time_futebol tm ON p.id_mandante = tm.id_time
            INNER JOIN arabetdb.time_futebol tv ON p.id_visitante = tv.id_time
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS arabetdb.vw_painel_jogos");
        DB::statement("
            CREATE VIEW arabetdb.vw_painel_jogos AS
            SELECT p.id_partida, tm.nome AS mandante, tv.nome AS visitante, p.data_hora,
                   p.odd_mandante, p.odd_empate, p.odd_visitante, p.status
            FROM arabetdb.partida p
            INNER JOIN arabetdb.time_futebol tm ON p.id_mandante = tm.id_time
            INNER JOIN arabetdb.time_futebol tv ON p.id_visitante = tv.id_time
        ");
    }
};
