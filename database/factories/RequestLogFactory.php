<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RequestLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ip_address'    => $this->faker->ipv4,
            'method'        => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'endpoint'      => '/' . $this->faker->slug,
            'status_code'   => $this->faker->randomElement([200, 201, 400, 401, 404, 500]),
            'duration_ms'   => $this->faker->numberBetween(10, 1000),
            'payload'       => ['sample' => 'data', 'user' => $this->faker->name],
            'response_body' => ['success' => true, 'message' => $this->faker->sentence],
            'created_at'    => now()->subMinutes(rand(0, 300)),
        ];
    }
}
