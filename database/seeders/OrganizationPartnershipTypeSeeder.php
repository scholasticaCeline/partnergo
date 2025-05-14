<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\OrganizationPartnershipType;
use App\Models\PartnershipType; 

class OrganizationPartnershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgs = Organization::all();
        $types = PartnershipType::all();

        foreach ($orgs as $org) {
            $randomTypes = $types->random(rand(1, 3));

            foreach ($randomTypes as $type) {
                OrganizationPartnershipType::create([
                    'OrganizationPartnershipTypeID' => Str::uuid(),
                    'OrganizationID' => $org->OrganizationID,
                    'PartnershipTypeID' => $type->PartnershipTypeID,
                ]);
            }
        }
    }
}
