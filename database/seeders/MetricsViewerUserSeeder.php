<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MetricsViewerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario con rol restrictivo para métricas
        User::updateOrCreate(
            ['email' => 'metrics@smartfilms.com'],
            [
                'name' => 'Visualizador de Métricas',
                'email' => 'metrics@smartfilms.com',
                'password' => Hash::make('metrics123'),
                'is_admin' => false,
                'role' => 'metrics_viewer',
                'participant_id' => null,
            ]
        );

        $this->command->info('Usuario con rol restrictivo creado:');
        $this->command->info('Email: metrics@smartfilms.com');
        $this->command->info('Password: metrics123');
        $this->command->info('Rol: metrics_viewer (solo puede ver métricas)');
    }
}
