<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArabetSeeder extends Seeder
{
    public function run(): void
    {
        // Limpeza preventiva mantendo a ordem de chaves estrangeiras
        DB::statement('TRUNCATE arabetdb.transacao, arabetdb.aposta, arabetdb.partida, arabetdb.time_futebol, arabetdb.apostador, arabetdb.usuario RESTART IDENTITY CASCADE');

        // Times
        DB::statement("INSERT INTO arabetdb.time_futebol (nome, cidade, estadio) VALUES
            ('CRB', 'Maceió', 'Rei Pelé'), ('CSA', 'Maceió', 'Rei Pelé'),
            ('ASA', 'Arapiraca', 'Coaracy da Mata Fonseca'), ('CSE', 'Palmeira dos Índios', 'Juca Sampaio'),
            ('Murici', 'Murici', 'José Gomes da Costa'), ('Coruripe', 'Coruripe', 'Gerson Amaral')");

        // Usuários Iniciais (Sem criptografia pgcrypto)
        DB::statement("INSERT INTO arabetdb.usuario (nome, cpf, email, senha, tipo) VALUES
            ('Admin Sistema', '00000000001', 'admin@alagoasbet.com', 'hash123', 'ADMINISTRADOR'),
            ('Carlos Silva', '11111111111', 'carlos@email.com', 'hash123', 'APOSTADOR'),
            ('Maria Oliveira', '22222222222', 'maria@email.com', 'hash123', 'APOSTADOR'),
            ('João Souza', '33333333333', 'joao@email.com', 'hash123', 'APOSTADOR'),
            ('Ana Lima', '44444444444', 'ana@email.com', 'hash123', 'APOSTADOR'),
            ('Pedro Mendes', '55555555555', 'pedro@email.com', 'hash123', 'APOSTADOR')");

        // Usuários Avançados (Com criptografia pgcrypto pgbcrypt)
        DB::statement("INSERT INTO arabetdb.usuario (nome, cpf, email, senha, tipo) VALUES
            ('Admin Garibaldo', '458746254', 'garibaldo@arabet.com', crypt('admin123', gen_salt('bf')), 'ADMINISTRADOR'),
            ('Mestre Yoda', '458455236', 'yoda@email.com', crypt('senha123', gen_salt('bf')), 'APOSTADOR'),
            ('Anakin Skywalker', '458452698', 'anakin@email.com', crypt('senha123', gen_salt('bf')), 'APOSTADOR'),
            ('Peter Parker', '254874125', 'peter@email.com', crypt('senha123', gen_salt('bf')), 'APOSTADOR'),
            ('Tony Stark', '546325415', 'tony@email.com', crypt('senha123', gen_salt('bf')), 'APOSTADOR'),
            ('Wagner Moura', '125478962', 'wagner@email.com', crypt('senha123', gen_salt('bf')), 'APOSTADOR')");

        // Apostadores
        DB::statement("INSERT INTO arabetdb.apostador (id_usuario, data_nascimento, saldo) VALUES
            (2, '1990-05-15', 500.00), (3, '1985-10-20', 150.50),
            (4, '1998-02-10', 1000.00), (5, '2001-07-30', 50.00), (6, '1995-12-05', 300.00)");

        // Partidas
        DB::statement("INSERT INTO arabetdb.partida (id_mandante, id_visitante, data_hora, odd_mandante, odd_empate, odd_visitante, status) VALUES
            (1, 2, '2026-05-30 16:00:00', 2.10, 3.10, 3.50, 'AGENDADA'),
            (3, 4, '2026-05-28 20:00:00', 1.80, 3.20, 4.00, 'AGENDADA'),
            (5, 1, '2026-05-25 15:00:00', 5.50, 4.00, 1.40, 'FINALIZADA'),
            (2, 6, '2026-05-26 19:00:00', 1.50, 3.80, 5.00, 'FINALIZADA'),
            (3, 1, '2026-06-05 16:00:00', 3.00, 3.20, 2.20, 'AGENDADA')");

        // Transações
        DB::statement("INSERT INTO arabetdb.transacao (id_apostador, tipo, valor) VALUES
            (2, 'DEPOSITO', 600.00), (2, 'SAQUE', 100.00), (3, 'DEPOSITO', 200.00),
            (3, 'SAQUE', 49.50), (4, 'DEPOSITO', 1500.00), (4, 'SAQUE', 500.00),
            (5, 'DEPOSITO', 50.00), (6, 'DEPOSITO', 400.00), (6, 'SAQUE', 100.00), (2, 'DEPOSITO', 50.00)");

        // Apostas
        DB::statement("INSERT INTO arabetdb.aposta (id_apostador, id_partida, valor, palpite, odd_momento) VALUES
            (2, 1, 100.00, 'MANDANTE', 2.10), (2, 2, 50.00, 'MANDANTE', 1.80),
            (3, 1, 20.00, 'EMPATE', 3.10), (4, 1, 500.00, 'VISITANTE', 3.50),
            (4, 3, 200.00, 'VISITANTE', 1.40), (5, 4, 10.00, 'MANDANTE', 1.50),
            (6, 2, 100.00, 'VISITANTE', 4.00), (6, 5, 50.00, 'EMPATE', 3.20),
            (2, 5, 80.00, 'MANDANTE', 3.00), (3, 4, 30.00, 'EMPATE', 3.80)");
    }
}