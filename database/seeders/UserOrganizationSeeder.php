<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserOrganization;
use App\Models\User;
use App\Models\Organization;

class UserOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $orgs = Organization::all();

        foreach ($users as $user) {
            $orgCount = rand(1, 3);

            for ($i = 0; $i < $orgCount; $i++) {
                UserOrganization::factory()->create([
                    'UserID' => $user->UserID,
                    'OrganizationID' => $orgs->random()->OrganizationID,
                ]);
            }
        }
    }
}
