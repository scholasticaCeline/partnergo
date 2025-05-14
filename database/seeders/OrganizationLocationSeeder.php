<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\OrganizationLocation;
use App\Models\Organization; 
use App\Models\Location; 

class OrganizationLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgs = Organization::all();
        $locations = Location::all();

        foreach ($orgs as $org) {
            $assignedLocations = $locations->random(rand(1, 3));

            foreach ($assignedLocations as $location) {
                OrganizationLocation::create([
                    'OrganizationLocationID' => Str::uuid(),
                    'OrganizationID' => $org->OrganizationID,
                    'LocationID' => $location->LocationID,
                ]);
            }
        }
    }
}
