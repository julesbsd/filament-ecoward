<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    protected $model = \App\Models\Action::class;

    public function definition(): array
    {
        return [
            'action_type_id' => 1,
            // 'trash_id' => \App\Models\Trash::factory(),
            'challenge_id' => rand(1,4),
            'user_id' => \App\Models\User::factory(),
            'image_action' => $this->faker->imageUrl(),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(['pending', 'accepted']),
            'location' => $this->faker->address(),
        ];
    }
}
