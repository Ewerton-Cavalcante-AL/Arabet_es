<?php

namespace Database\Factories;

use App\Models\Apostador;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Apostador>
 */
class ApostadorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Adapte para o nome das colunas reais que você tem na tabela no banco
            'id_usuario' => User::factory(),
            'data_nascimento' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'saldo' => 100.00, // Um valor padrão para os testes
            // Se tiver senha ou outros campos obrigatórios (NOT NULL), coloque aqui também
        ];
    }
}
