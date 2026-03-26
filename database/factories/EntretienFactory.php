<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entretien>
 */
class EntretienFactory extends Factory
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
            'date_heure' => fake()->dateTimeBetween('now', '+2 months'),
            'lieu' => fake()->randomElement([
                'Salle de réunion A101',
                'Salle de réunion B205',
                'Salle de conférence principale',
                'Bureau du responsable',
                'Salle d\'entretien RH',
                'Visioconférence'
            ]),
            'lien_reunion' => fake()->optional(0.3)->url(), // 30% de chance d'avoir un lien
            'notes' => fake()->optional(0.7)->paragraph(), // 70% de chance d'avoir des notes
            'documents_demande' => fake()->optional(0.5)->filePath(), // 50% de chance d'avoir des documents
            'realise' => fake()->boolean(60), // 60% de chance d'être réalisé
            'users_id' => null, // Sera défini après création des users
        ];
    }

    /**
     * Indicate that the entretien is realized.
     */
    public function realized(): static
    {
        return $this->state(fn (array $attributes) => [
            'realise' => true,
        ]);
    }

    /**
     * Indicate that the entretien is not realized yet.
     */
    public function notRealized(): static
    {
        return $this->state(fn (array $attributes) => [
            'realise' => false,
        ]);
    }

    /**
     * Indicate that the entretien is a visio conference.
     */
    public function visio(): static
    {
        return $this->state(fn (array $attributes) => [
            'lieu' => 'Visioconférence',
            'lien_reunion' => fake()->url(),
        ]);
    }

    /**
     * Indicate that the entretien has notes.
     */
    public function withNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => fake()->paragraph(),
        ]);
    }
}