<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DomainCheck>
 */
class DomainCheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'status_code' => 200,
            'response_time' => $this->faker->numberBetween(100, 2000),
            'is_successful' => true,
            'error_message' => null,
            'created_at' => now(),
        ];
    }
}
