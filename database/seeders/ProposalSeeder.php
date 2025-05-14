<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Organization;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $orgs = Organization::all();

        foreach ($users as $user) {
            $proposalCount = rand(1, 3);

            for ($i = 0; $i < $proposalCount; $i++) {
                $org = $orgs->random();

                Proposal::factory()->create([
                    'UserID' => $user->UserID,
                    'OrganizationID' => $org->OrganizationID,
                ]);
            }
        }
    }
}
