<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class TimeFutebolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // O 'unique()' evita que o Faker gere o mesmo nome duas vezes e quebre a constraint UNIQUE
            'nome' => $this->faker->unique()->word() . ' Alagoano FC',
            'cidade' => $this->faker->city(),
            'estadio' => $this->faker->word() . ' Arena',
        ];
    }
}
