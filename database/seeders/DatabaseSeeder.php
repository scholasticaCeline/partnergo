<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your individual seeders here
        $this->call([
            PartnershipTypeSeeder::class,
            IndustryTypeSeeder::class,
            LocationSeeder::class,
            UserSeeder::class,
            OrganizationSeeder::class,
            UserOrganizationSeeder::class,
            OrganizationLocationSeeder::class,
            OrganizationIndustrySeeder::class,
            OrganizationPartnershipTypeSeeder::class,
            ProposalSeeder::class,
            ProposalFileSeeder::class,
            PartnershipSeeder::class,
            MessageSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
