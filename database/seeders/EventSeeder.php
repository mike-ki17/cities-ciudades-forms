<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'Smart Films Festival',
                'city' => 'Bogotá',
                'year' => 2024,
            ],
            [
                'name' => 'Smart Films Festival',
                'city' => 'Lima',
                'year' => 2024,
            ],
            [
                'name' => 'Smart Films Festival',
                'city' => 'Quito',
                'year' => 2024,
            ],
            [
                'name' => 'Smart Films Festival',
                'city' => 'Bogotá',
                'year' => 2025,
            ],
            [
                'name' => 'Smart Films Festival',
                'city' => 'Lima',
                'year' => 2025,
            ],
            [
                'name' => 'Smart Films Festival',
                'city' => 'Quito',
                'year' => 2025,
            ],
        ];

        foreach ($events as $eventData) {
            Event::firstOrCreate(
                [
                    'name' => $eventData['name'],
                    'city' => $eventData['city'],
                    'year' => $eventData['year'],
                ],
                $eventData
            );
        }
    }
}