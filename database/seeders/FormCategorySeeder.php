<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de formulario
        $generoCategory = \App\Models\FormCategory::firstOrCreate(
            ['code' => 'genero'],
            [
                'name' => 'Género',
                'description' => 'Selección de género del participante',
                'is_active' => true
            ]
        );

        $tipoCategory = \App\Models\FormCategory::firstOrCreate(
            ['code' => 'tipo_participante'],
            [
                'name' => 'Tipo de Participante',
                'description' => 'Tipo de participación en el evento',
                'is_active' => true
            ]
        );

        // Crear opciones para género
        \App\Models\FormOption::firstOrCreate(
            ['category_id' => $generoCategory->id, 'value' => 'masculino'],
            [
                'label' => 'Masculino',
                'order' => 1,
                'is_active' => true
            ]
        );

        \App\Models\FormOption::firstOrCreate(
            ['category_id' => $generoCategory->id, 'value' => 'femenino'],
            [
                'label' => 'Femenino',
                'order' => 2,
                'is_active' => true
            ]
        );

        // Crear opciones para tipo de participante
        \App\Models\FormOption::firstOrCreate(
            ['category_id' => $tipoCategory->id, 'value' => 'director'],
            [
                'label' => 'Director',
                'order' => 1,
                'is_active' => true
            ]
        );

        \App\Models\FormOption::firstOrCreate(
            ['category_id' => $tipoCategory->id, 'value' => 'productor'],
            [
                'label' => 'Productor',
                'order' => 2,
                'is_active' => true
            ]
        );

        \App\Models\FormOption::firstOrCreate(
            ['category_id' => $tipoCategory->id, 'value' => 'actor'],
            [
                'label' => 'Actor/Actriz',
                'order' => 3,
                'is_active' => true
            ]
        );

        $this->command->info('Categorías y opciones de formulario creadas exitosamente');
    }
}
