<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IndustryType;
use Illuminate\Support\Str;

class IndustryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Technology',
            'Healthcare',
            'Energy',
            'Entertainment',
            'Media',
            'Retail',
            'Education',
            'Hospitality',
            'Cybersecurity',
            'Sports and Recreation',
            'Advertising and Marketing',
            'Consulting',
            'Investment',
            'Publishing',
            'Gaming',
            'Social Services',
            'Event Management',
            'Architecture',
        ];

        foreach ($types as $type) {
            IndustryType::create([
                'IndustryTypeID' => (string) Str::uuid(),
                'IndustryType' => $type,
            ]);
        }
    }
}
