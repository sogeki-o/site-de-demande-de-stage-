<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceUCA>
 */
class ServiceUCAFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->company(),
            'description' => fake()->paragraph(),
            'responsable_nom' => fake()->name(),
            'responsable_email' => fake()->email(),
            'actif' => fake()->boolean(95), // 95% de chance d'être actif
        ];
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }

    /**
     * Create a service without responsable.
     */
    public function withoutResponsable(): static
    {
        return $this->state(fn (array $attributes) => [
            'responsable_nom' => null,
            'responsable_email' => null,
        ]);
    }
}