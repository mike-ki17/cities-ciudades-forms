<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un ciclo para el evento 1 si no existe
        $cycle = \App\Models\Cycle::firstOrCreate(
            ['events_id' => 1],
            [
                'name' => 'Ciclo Principal',
                'days' => 30,
                'is_active' => true
            ]
        );

        // Crear asistencias para algunos participantes
        $participants = \App\Models\Participant::limit(5)->get();
        
        foreach ($participants as $participant) {
            \App\Models\Attendance::firstOrCreate(
                [
                    'participant_id' => $participant->id,
                    'cycle_id' => $cycle->id
                ],
                [
                    'attended' => true,
                    'attended_at' => now()
                ]
            );
        }

        $this->command->info('Asistencias creadas para ' . $participants->count() . ' participantes');
    }
}
