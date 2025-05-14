<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProposalFile;
use App\Models\User;
use App\Models\Proposal;

class ProposalFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $proposals = Proposal::all();

        foreach ($proposals as $proposal) {
            ProposalFile::factory()->create([
                'UploadedBy' => $users->random()->UserID,
                'ProposalID' => $proposal->ProposalID,
            ]);
        }
    }
}
