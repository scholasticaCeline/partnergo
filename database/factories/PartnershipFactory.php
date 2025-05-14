<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Partnership;
use App\Models\PartnershipType;
use App\Models\User;
use App\Models\Organization;
use App\Models\Proposal;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partnership>
 */
class PartnershipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'PartnershipID' => (string) Str::uuid(),
            'OrganizationSenderID' => Organization::inRandomOrder()->first()->OrganizationID,
            'OrganizationTargetID' => Organization::inRandomOrder()->first()->OrganizationID,
            'PartnershipTypeID' => PartnershipType::inRandomOrder()->first()->PartnershipTypeID,
            'ProposalID' => Proposal::inRandomOrder()->first()->ProposalID,
            'Status' => $this->faker->randomElement(['Active', 'Pending', 'Closed']),
            'CreatedAt' => $this->faker->date,
            'StartDate' => $this->faker->date,
            'EndDate' => $this->faker->date,
        ];
    }
}
