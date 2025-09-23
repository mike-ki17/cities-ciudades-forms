<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormCategory;
use App\Models\FormOption;

class TestFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear campo de género
        $genero = FormCategory::create([
            'code' => 'genero',
            'name' => 'Género',
            'description' => 'Campo para seleccionar el género de la persona',
            'is_active' => true
        ]);

        // Crear opciones para género
        FormOption::create(['category_id' => $genero->id, 'value' => 'masculino', 'label' => 'Masculino', 'order' => 1, 'is_active' => true]);
        FormOption::create(['category_id' => $genero->id, 'value' => 'femenino', 'label' => 'Femenino', 'order' => 2, 'is_active' => true]);
        FormOption::create(['category_id' => $genero->id, 'value' => 'otro', 'label' => 'Otro', 'order' => 3, 'is_active' => true]);

        // Crear campo de edad
        $edad = FormCategory::create([
            'code' => 'edad',
            'name' => 'Rango de Edad',
            'description' => 'Campo para seleccionar el rango de edad',
            'is_active' => true
        ]);

        // Crear opciones para edad
        FormOption::create(['category_id' => $edad->id, 'value' => '18-25', 'label' => '18-25 años', 'order' => 1, 'is_active' => true]);
        FormOption::create(['category_id' => $edad->id, 'value' => '26-35', 'label' => '26-35 años', 'order' => 2, 'is_active' => true]);
        FormOption::create(['category_id' => $edad->id, 'value' => '36-45', 'label' => '36-45 años', 'order' => 3, 'is_active' => true]);
        FormOption::create(['category_id' => $edad->id, 'value' => '46-55', 'label' => '46-55 años', 'order' => 4, 'is_active' => true]);
        FormOption::create(['category_id' => $edad->id, 'value' => '56+', 'label' => '56+ años', 'order' => 5, 'is_active' => true]);

        // Crear campo de ciudad
        $ciudad = FormCategory::create([
            'code' => 'ciudad',
            'name' => 'Ciudad de Residencia',
            'description' => 'Campo para seleccionar la ciudad donde reside',
            'is_active' => true
        ]);

        // Crear opciones para ciudad
        FormOption::create(['category_id' => $ciudad->id, 'value' => 'bogota', 'label' => 'Bogotá', 'order' => 1, 'is_active' => true]);
        FormOption::create(['category_id' => $ciudad->id, 'value' => 'medellin', 'label' => 'Medellín', 'order' => 2, 'is_active' => true]);
        FormOption::create(['category_id' => $ciudad->id, 'value' => 'cali', 'label' => 'Cali', 'order' => 3, 'is_active' => true]);
        FormOption::create(['category_id' => $ciudad->id, 'value' => 'barranquilla', 'label' => 'Barranquilla', 'order' => 4, 'is_active' => true]);
        FormOption::create(['category_id' => $ciudad->id, 'value' => 'otra', 'label' => 'Otra', 'order' => 5, 'is_active' => true]);

        $this->command->info('Campos de ejemplo creados exitosamente!');
    }
}
