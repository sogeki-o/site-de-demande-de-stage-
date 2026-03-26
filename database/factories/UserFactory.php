<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'telephone' => fake()->phoneNumber(),
            'role' => fake()->randomElement(['demandeur', 'rh', 'service', 'admin']),
            'service_uca_id' => null,
            'actif' => fake()->boolean(90), 
        ];
    }

    
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is from RH.
     */
    public function rh(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'rh',
        ]);
    }

    /**
     * Indicate that the user is from a service.
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'service',
        ]);
    }

    /**
     * Indicate that the user is a demandeur.
     */
    public function demandeur(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'demandeur',
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'actif' => false,
        ]);
    }
}