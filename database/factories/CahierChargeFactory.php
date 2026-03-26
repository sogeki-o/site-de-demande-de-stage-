<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CahierCharge>
 */
class CahierChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'demande_stage_id' => null, // Sera défini après création des demandes
            'sujet_stage' => fake()->sentence(8),
            'description' => fake()->paragraphs(3, true),
            'fichier_path' => fake()->filePath(),
            'date_partage' => fake()->dateTimeBetween('-1 month', 'now'),
            'partage_par' => null, // Sera défini après création des users
        ];
    }

    /**
     * Indicate that the cahier de charge was shared recently.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_partage' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the cahier de charge was shared a while ago.
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_partage' => fake()->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }

    /**
     * Create a cahier de charge with a specific subject.
     */
    public function withSubject(string $subject): static
    {
        return $this->state(fn (array $attributes) => [
            'sujet_stage' => $subject,
        ]);
    }
}