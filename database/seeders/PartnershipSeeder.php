<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partnership;
use App\Models\PartnershipType;
use App\Models\User;
use App\Models\Organization;
use App\Models\Proposal;

class PartnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgs = Organization::all();
        $proposals = Proposal::all();
        $types = PartnershipType::all();

        foreach ($proposals as $proposal) {
            Partnership::factory()->create([
                'OrganizationSenderID' => $orgs->random()->OrganizationID,
                'OrganizationTargetID' => $orgs->random()->OrganizationID,
                'PartnershipTypeID' => $types->random()->PartnershipTypeID,
                'ProposalID' => $proposal->ProposalID,
            ]);
        }
    }
}
