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
                'country' => 'Internacional',
            ],
            [
                'name' => 'Bogotá',
                'country' => 'Colombia',
            ],
            [
                'name' => 'Lima',
                'country' => 'Perú',
            ],
            [
                'name' => 'Quito',
                'country' => 'Ecuador',
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