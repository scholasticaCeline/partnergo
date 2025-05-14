<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\OrganizationIndustryType;
use App\Models\IndustryType; 

class OrganizationIndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgs = Organization::all();
        $industries = IndustryType::all();

        foreach ($orgs as $org) {
            $randomIndustries = $industries->random(rand(1, 3));

            foreach ($randomIndustries as $industry) {
                OrganizationIndustryType::create([
                    'OrganizationIndustryID' => Str::uuid(),
                    'OrganizationID' => $org->OrganizationID,
                    'IndustryTypeID' => $industry->IndustryTypeID,
                ]);
            }
        }
    }
}
