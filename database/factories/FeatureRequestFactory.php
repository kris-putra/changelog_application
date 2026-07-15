<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FeatureRequestFactory extends Factory
{
    public function definition()
    {
        $title = $this->faker->sentence(6);
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->lexify('??????'),
            'description' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement(['feature','change','bug']),
            'priority' => $this->faker->randomElement(['low','medium','high','urgent']),
            'status' => 'Open',
            'requested_by' => 1,
            'assigned_to' => null,
            'metadata' => null,
            'votes_count' => 0,
        ];
    }
}
