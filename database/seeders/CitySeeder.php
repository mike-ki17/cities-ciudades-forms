<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'General',
                'timezone' => 'UTC',
            ],
            [
                'name' => 'Bogotá',
                'timezone' => 'America/Bogota',
            ],
            [
                'name' => 'Lima',
                'timezone' => 'America/Lima',
            ],
            [
                'name' => 'Quito',
                'timezone' => 'America/Guayaquil',
            ],
        ];

        foreach ($cities as $cityData) {
            City::firstOrCreate(
                ['name' => $cityData['name']],
                $cityData
            );
        }
    }
}