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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['submitted', 'accepted', 'rejected', 'pending'];

        return [
            'ProposalID'      => Str::uuid(),
            'ProposalTitle'   => $this->faker->bs() . ' Initiative',
            'Description'     => $this->faker->paragraph(4),
            'ProposalStatus'  => $this->faker->randomElement($statuses),
            'StartDate'       => null,
            'EndDate'         => null,
        ];
    }
}