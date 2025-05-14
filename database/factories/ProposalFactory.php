<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Organization;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ProposalID' => (string) Str::uuid(), // <-- add this
            'OrganizationID' => Organization::inRandomOrder()->first()->OrganizationID ?? (string) Str::uuid(),
            'ProposalTitle' => $this->faker->company,
            'ProposalStatus' => $this->faker->randomElement(['pending', 'rejected', 'accepted']),
            'UserID' => User::inRandomOrder()->first()->UserID ?? (string) Str::uuid(),
        ];
    }
}
