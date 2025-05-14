<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    public function definition(): array
    {
        
        return [
            'OrganizationID' => (string) Str::uuid(),
            'Name' => $this->faker->company,
            'Description' => $this->faker->catchPhrase,
            'OrganizationType' => $this->faker->randomElement(['Startup', 'Company', 'UMKM', 'NGO', 'Community']),
            'OpenForPartnership' => $this->faker->boolean,
        ];
    }
}
