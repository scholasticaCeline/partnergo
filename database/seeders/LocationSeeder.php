<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indonesianCities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Makassar', 'Yogyakarta', 'Semarang', 'Bali', 'Palembang', 'Malang',
            'Samarinda', 'Batam', 'Pekanbaru', 'Ambon', 'Mataram', 'Denpasar', 'Tangerang', 'Cirebon', 'Banjarmasin',
            'Bogor', 'Balikpapan', 'Sukabumi', 'Kupang', 'Pontianak', 'Manado', 'Jambi', 'Padang', 'Surakarta', 'Bekasi'
        ];

        foreach ($indonesianCities as $city) {
            Location::create([
                'LocationID' => (string) Str::uuid(),
                'LocationName' => $city,
            ]);
        }
    }
}
