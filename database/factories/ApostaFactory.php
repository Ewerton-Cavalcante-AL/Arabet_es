<?php

namespace Database\Factories;

use App\Models\Aposta;
use App\Models\Apostador;
use App\Models\Partida;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aposta>
 */
class ApostaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_aposta' => $this->faker->unique()->randomNumber(),
            'id_apostador' => Apostador::factory(), // Cria um apostador usando a factory de Apostador
            'id_partida' => Partida::factory(), // Cria uma partida usando a
            'valor' => $this->faker->randomFloat(2, 10, 100), // Valor da aposta entre 10 e 100
            'palpite' => $this->faker->randomElement(['MANDANTE', 'EMPATE', 'VISITANTE']), // Palpite aleatório
            'odd_momento' => $this->faker->randomFloat(2, 1, 5), // Odd aleatória entre 1 e 5
            'status' => 'PENDENTE', // Status padrão para os testes
            'data_aposta' => $this->faker->dateTimeBetween('-1 month', 'now'), // Data da aposta nos últimos 30 dias
        ];
    }
}
