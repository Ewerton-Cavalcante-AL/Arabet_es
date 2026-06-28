<?php

namespace Database\Factories;

use App\Models\Partida;
use App\Models\TimeFutebol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partida>
 */
class PartidaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_partida' => $this->faker->unique()->randomNumber(),
            'id_mandante' => TimeFutebol::factory(), // Cria um time mandante usando a factory de TimeFutebol
            'id_visitante' => TimeFutebol::factory(), // Cria um time visitante usando a factory de TimeFutebol
            'data_hora' => $this->faker->dateTime(),
            'status' => 'AGENDADA', // Status padrão para os testes
            'odd_mandante' => $this->faker->randomFloat(2, 1, 5),
            'odd_empate' => $this->faker->randomFloat(2, 1, 5),
            'odd_visitante' => $this->faker->randomFloat(2, 1, 5),
            'placar_mandante' => 0,
            'placar_visitante' => 0,
        ];
    }
}
