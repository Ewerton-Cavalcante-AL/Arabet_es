<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (app()->environment('testing')) {
            DB::statement('DROP SCHEMA IF EXISTS arabetdb CASCADE');
        }
        // Cria o schema isolado no Postgres
        DB::statement('CREATE SCHEMA IF NOT EXISTS arabetdb');
        // Garante a extensão para criptografia usada nos inserts
        DB::statement('CREATE EXTENSION IF NOT EXISTS pgcrypto');
    }

    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS arabetdb CASCADE');
    }
};