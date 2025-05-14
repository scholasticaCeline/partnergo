<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Proposal;
use App\Models\Notification;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'NotificationID' => (string) Str::uuid(),
            'TargetType' => $this->faker->randomElement(['Proposal', 'Message', 'Partnership']),
            'TargetID' => (string) Str::uuid(),
            'Content' => $this->faker->sentence,
        ];
    }
}
