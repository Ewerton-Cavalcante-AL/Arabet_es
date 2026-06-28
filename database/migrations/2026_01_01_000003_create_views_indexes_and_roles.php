<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Views
        DB::statement("
            CREATE VIEW arabetdb.vw_painel_jogos AS
            SELECT p.id_partida, tm.nome AS mandante, tv.nome AS visitante, p.data_hora,
                   p.odd_mandante, p.odd_empate, p.odd_visitante, p.status
            FROM arabetdb.partida p
            INNER JOIN arabetdb.time_futebol tm ON p.id_mandante = tm.id_time
            INNER JOIN arabetdb.time_futebol tv ON p.id_visitante = tv.id_time
        ");

        DB::statement("
            CREATE VIEW arabetdb.vw_apostador_publico AS
            SELECT id_usuario, nome, email FROM arabetdb.usuario WHERE tipo = 'APOSTADOR'
        ");

        DB::statement("
            CREATE VIEW arabetdb.vw_extrato_apostador AS
            SELECT u.id_usuario, u.nome, u.email, ap.saldo, a.id_aposta, a.id_partida,
                   tm.nome AS time_mandante, tv.nome AS time_visitante,
                   (tm.nome || ' x ' || tv.nome) AS confronto,
                   a.valor AS valor_aposta, a.palpite, a.odd_momento, a.status AS status_aposta, a.data_aposta
            FROM arabetdb.usuario u
            INNER JOIN arabetdb.apostador ap ON u.id_usuario = ap.id_usuario
            LEFT JOIN arabetdb.aposta a ON ap.id_usuario = a.id_apostador
            LEFT JOIN arabetdb.partida p ON a.id_partida = p.id_partida
            LEFT JOIN arabetdb.time_futebol tm ON p.id_mandante = tm.id_time
            LEFT JOIN arabetdb.time_futebol tv ON p.id_visitante = tv.id_time
        ");

        // 2. Índices
        DB::statement("CREATE INDEX idx_partida_status_data ON arabetdb.partida(status, data_hora)");
        DB::statement("CREATE INDEX idx_aposta_id_apostador ON arabetdb.aposta(id_apostador)");
        DB::statement("CREATE INDEX idx_usuario_nome ON arabetdb.usuario(nome)");

        // 3. Roles e Permissões (Tratadas com blocos anônimos para evitar erros caso a role já exista no servidor local)
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = 'gestor_sistema') THEN CREATE ROLE gestor_sistema; END IF; END $$");
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = 'analista_riscos') THEN CREATE ROLE analista_riscos; END IF; END $$");
        DB::statement("DO $$ BEGIN IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = 'operador_jogos') THEN CREATE ROLE operador_jogos; END IF; END $$");

        DB::statement("GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA arabetdb TO gestor_sistema");
        DB::statement("GRANT SELECT ON arabetdb.aposta, arabetdb.transacao, arabetdb.apostador TO analista_riscos");
        DB::statement("GRANT SELECT, insert, update ON arabetdb.partida, arabetdb.time_futebol TO operador_jogos");
        DB::statement("REVOKE ALL ON arabetdb.usuario FROM operador_jogos");
        DB::statement("GRANT SELECT ON arabetdb.vw_apostador_publico TO operador_jogos");
    }

    public function down(): void
    {
        DB::statement("DROP ROLE IF EXISTS gestor_sistema, analista_riscos, operador_jogos");
        DB::statement("DROP VIEW IF EXISTS arabetdb.vw_extrato_apostador, arabetdb.vw_apostador_publico, arabetdb.vw_painel_jogos");
    }
};