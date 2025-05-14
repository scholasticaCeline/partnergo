<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PartnershipType;
use Illuminate\Support\Str;

class PartnershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Co-Marketing',
            'Sponsorship',
            'Product Collaboration',
            'Content Creation',
            'Affiliate',
            'Event Collaboration',
            'CSR Project',
        ];

        foreach ($types as $type) {
            PartnershipType::create([
                'PartnershipTypeID' => (string) Str::uuid(),
                'PartnershipTypeName' => $type,
            ]);
        }
    }
}
