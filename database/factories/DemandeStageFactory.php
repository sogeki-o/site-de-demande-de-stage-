<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DemandeStage>
 */
class DemandeStageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null, // Sera défini après création des users
            'niveau_etude' => fake()->randomElement(['L1', 'L2', 'L3', 'M1', 'M2', 'Doctorat']),
            'etablissement' => fake()->company(),
            'filiere' => fake()->randomElement([
                'Informatique', 'Électronique', 'Mécanique', 'Génie Civil',
                'Biologie', 'Chimie', 'Physique', 'Mathématiques', 'Économie',
                'Gestion', 'Droit', 'Lettres', 'Langues', 'Histoire']),
            'duree_stage' => fake()->randomElement([1, 2, 3, 4, 5, 6]),
            'date_debut_prevue' => fake()->dateTimeBetween('now', '+6 months'),
            'service_uca_id' => null, // ça va etre defini apres creation des services
            'cv_path' => fake()->filePath(), //j'ai choisie path file car si on telecharge tous le PDF la base va remplire rapidement 
            'statut' => fake()->randomElement([
                'brouillon',
                'soumise',
                'en_cours_traitement_rh',
                'refusee_rh',
                'acceptee_rh',
                'affectee_service',
                'refusee_service',
                'entretien_planifie',
                'entretien_realise',
                'sujet_renseigne',
                'cahier_charges_partage',
                'cloturee'
            ]),
            'motif_refus' => null, 
            'date_soumission' => fake()->dateTimeBetween('-3 months', 'now'),
            'date_traitement_rh' => null,
            'date_affectation' => null,
            'traite_par' => null, 
        ];
    }

    /**
     * Indicate that the demande is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'acceptee_rh',
            'date_traitement_rh' => fake()->dateTimeBetween($attributes['date_soumission'], 'now'),
        ]);
    }

    /**
     * Indicate that the demande is refused.
     */
    public function refused(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'refusee_rh',
            'motif_refus' => fake()->sentence(),
            'date_traitement_rh' => fake()->dateTimeBetween($attributes['date_soumission'], 'now'),
        ]);
    }

    /**
     * Indicate that the demande is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'affectee_service',
            'date_traitement_rh' => fake()->dateTimeBetween($attributes['date_soumission'], 'now'),
            'date_affectation' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the demande is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'cloturee',
            'date_traitement_rh' => fake()->dateTimeBetween($attributes['date_soumission'], '-1 month'),
            'date_affectation' => fake()->dateTimeBetween('-2 months', '-1 month'),
        ]);
    }

    /**
     * Indicate that the demande is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'soumise',
            'date_traitement_rh' => null,
            'date_affectation' => null,
            'traite_par' => null,
        ]);
    }
}