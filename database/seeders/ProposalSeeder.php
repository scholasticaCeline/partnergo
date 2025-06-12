<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Proposal;
use App\Models\PartnershipType;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $organizations = Organization::all();
        $partnershipTypes = PartnershipType::all();

        if ($users->isEmpty() || $organizations->isEmpty() || $partnershipTypes->isEmpty()) {
            $this->command->info('Cannot run ProposalSeeder because a required table is empty.');
            return;
        }

        foreach ($users as $user) {
            $proposalCount = rand(1, 3);

            for ($i = 0; $i < $proposalCount; $i++) {
                $org = $organizations->random();
                $partnershipType = $partnershipTypes->random();

                Proposal::factory()->create([
                    // THIS IS THE FINAL FIX: Using the correct primary key from your User model
                    'UserID' => $user->UserID,

                    'OrganizationID' => $org->OrganizationID,
                    'PartnershipTypeID' => $partnershipType->PartnershipTypeID,
                ]);
            }
        }
    }
}