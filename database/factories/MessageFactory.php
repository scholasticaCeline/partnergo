<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Message;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'MessageID' => (string) Str::uuid(),
            'SenderID' => User::inRandomOrder()->first()->UserID,
            'ReceiverID' => User::inRandomOrder()->first()->UserID,
            'Content' => $this->faker->paragraph,
            'CreatedAt' => $this->faker->date,
            'ReadAt' => $this->faker->optional()->date,
        ];
    }
}
