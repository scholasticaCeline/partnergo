<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Organization;
use App\Models\UserOrganization;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserOrganization>
 */
class UserOrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UserOrganizationID' => (string) Str::uuid(), 
            'UserID' => User::inRandomOrder()->first()->UserID,
            'OrganizationID' => Organization::inRandomOrder()->first()->OrganizationID,
            'IsAdmin' => rand(0, 1),
        ];
    }
}
