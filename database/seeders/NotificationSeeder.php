<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Proposal;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $proposals = Proposal::all();

        foreach ($users as $user) {
            Notification::factory()->create([
                'TargetType' => 'User',
                'TargetID' => $user->UserID,
            ]);
        }

        foreach ($proposals as $proposal) {
            Notification::factory()->create([
                'TargetType' => 'Proposal',
                'TargetID' => $proposal->ProposalID,
            ]);
        }
    }
}
