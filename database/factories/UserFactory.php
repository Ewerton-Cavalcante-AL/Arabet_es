<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Model>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_usuario' => $this->faker->unique()->randomNumber(),
            'nome' => $this->faker->name(),
            'cpf' => $this->faker->unique()->numerify('###########'), 
            'email' => $this->faker->unique()->safeEmail(),
            'senha' => Hash::make('senha123'),
            'tipo' => 'APOSTADOR',
        ];
    }
}
