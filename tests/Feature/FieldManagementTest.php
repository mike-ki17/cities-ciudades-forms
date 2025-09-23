<?php

namespace Tests\Feature;

use App\Models\FormCategory;
use App\Models\FormOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FieldManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminUser = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);
    }

    /**
     * Test: Listar campos (categorías) como administrador
     */
    public function test_admin_can_view_fields_index()
    {
        FormCategory::factory()->count(5)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.fields.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.fields.index');
        $response->assertViewHas('categories');
    }

    /**
     * Test: Crear campo (categoría) exitosamente
     */
    public function test_admin_can_create_field()
    {
        $fieldData = [
            'code' => 'genero',
            'name' => 'Género',
            'description' => 'Campo para seleccionar el género',
            'is_active' => true
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.store'), $fieldData);

        $response->assertRedirect(route('admin.fields.index'));
        $response->assertSessionHas('success', 'Campo creado exitosamente.');
        
        $this->assertDatabaseHas('form_categories', $fieldData);
    }

    /**
     * Test: Validaciones al crear campo
     */
    public function test_create_field_validation()
    {
        // Test con datos faltantes
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.store'), []);

        $response->assertSessionHasErrors(['code', 'name']);

        // Test con código duplicado
        FormCategory::factory()->create(['code' => 'codigo-existente']);
        
        $invalidData = [
            'code' => 'codigo-existente',
            'name' => 'Nuevo Campo'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.store'), $invalidData);

        $response->assertSessionHasErrors(['code']);
    }

    /**
     * Test: Ver detalles de un campo
     */
    public function test_admin_can_view_field_details()
    {
        $field = FormCategory::factory()->create();
        
        // Crear opciones asociadas
        FormOption::factory()->count(3)->create(['category_id' => $field->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.fields.show', $field));

        $response->assertStatus(200);
        $response->assertViewIs('admin.fields.show');
        $response->assertViewHas('field');
    }

    /**
     * Test: Editar campo
     */
    public function test_admin_can_edit_field()
    {
        $field = FormCategory::factory()->create([
            'code' => 'codigo-original',
            'name' => 'Campo Original',
            'description' => 'Descripción original'
        ]);

        $updatedData = [
            'code' => 'codigo-actualizado',
            'name' => 'Campo Actualizado',
            'description' => 'Descripción actualizada',
            'is_active' => true
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.fields.update', $field), $updatedData);

        $response->assertRedirect(route('admin.fields.index'));
        $response->assertSessionHas('success', 'Campo actualizado exitosamente.');
        
        $this->assertDatabaseHas('form_categories', [
            'id' => $field->id,
            'code' => 'codigo-actualizado',
            'name' => 'Campo Actualizado'
        ]);
    }

    /**
     * Test: Eliminar campo sin opciones asociadas
     */
    public function test_admin_can_delete_field_without_options()
    {
        $field = FormCategory::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.fields.destroy', $field));

        $response->assertRedirect(route('admin.fields.index'));
        $response->assertSessionHas('success', 'Campo eliminado exitosamente.');
        
        $this->assertDatabaseMissing('form_categories', ['id' => $field->id]);
    }

    /**
     * Test: No se puede eliminar campo con opciones asociadas
     */
    public function test_cannot_delete_field_with_associated_options()
    {
        $field = FormCategory::factory()->create();
        FormOption::factory()->create(['category_id' => $field->id]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.fields.destroy', $field));

        $response->assertRedirect(route('admin.fields.index'));
        $response->assertSessionHas('error', 'No se puede eliminar el campo porque tiene opciones asociadas. Elimina primero las opciones.');
        
        $this->assertDatabaseHas('form_categories', ['id' => $field->id]);
    }

    /**
     * Test: Activar/desactivar campo
     */
    public function test_admin_can_toggle_field_status()
    {
        $field = FormCategory::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.toggle-status', $field));

        $response->assertRedirect(route('admin.fields.index'));
        $response->assertSessionHas('success', 'Campo activado exitosamente.');
        
        $this->assertDatabaseHas('form_categories', [
            'id' => $field->id,
            'is_active' => true
        ]);
    }

    /**
     * Test: Listar opciones de un campo
     */
    public function test_admin_can_view_field_options()
    {
        $field = FormCategory::factory()->create();
        FormOption::factory()->count(5)->create(['category_id' => $field->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.fields.options', $field));

        $response->assertStatus(200);
        $response->assertViewIs('admin.fields.options.index');
        $response->assertViewHas('field');
        $response->assertViewHas('options');
    }

    /**
     * Test: Crear opción para un campo
     */
    public function test_admin_can_create_field_option()
    {
        $field = FormCategory::factory()->create();

        $optionData = [
            'value' => 'masculino',
            'label' => 'Masculino',
            'description' => 'Opción para género masculino',
            'order' => 1,
            'is_active' => true
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.options.store', $field), $optionData);

        $response->assertRedirect(route('admin.fields.options', $field));
        $response->assertSessionHas('success', 'Opción creada exitosamente.');
        
        $this->assertDatabaseHas('form_options', [
            'category_id' => $field->id,
            'value' => 'masculino',
            'label' => 'Masculino'
        ]);
    }

    /**
     * Test: Validaciones al crear opción
     */
    public function test_create_option_validation()
    {
        $field = FormCategory::factory()->create();

        // Test con datos faltantes
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.options.store', $field), []);

        $response->assertSessionHasErrors(['value', 'label']);
    }

    /**
     * Test: Editar opción de campo
     */
    public function test_admin_can_edit_field_option()
    {
        $field = FormCategory::factory()->create();
        $option = FormOption::factory()->create([
            'category_id' => $field->id,
            'value' => 'valor-original',
            'label' => 'Etiqueta Original'
        ]);

        $updatedData = [
            'value' => 'valor-actualizado',
            'label' => 'Etiqueta Actualizada',
            'description' => 'Descripción actualizada',
            'order' => 2,
            'is_active' => true
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.fields.options.update', [$field, $option]), $updatedData);

        $response->assertRedirect(route('admin.fields.options', $field));
        $response->assertSessionHas('success', 'Opción actualizada exitosamente.');
        
        $this->assertDatabaseHas('form_options', [
            'id' => $option->id,
            'value' => 'valor-actualizado',
            'label' => 'Etiqueta Actualizada'
        ]);
    }

    /**
     * Test: Eliminar opción de campo
     */
    public function test_admin_can_delete_field_option()
    {
        $field = FormCategory::factory()->create();
        $option = FormOption::factory()->create(['category_id' => $field->id]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.fields.options.destroy', [$field, $option]));

        $response->assertRedirect(route('admin.fields.options', $field));
        $response->assertSessionHas('success', 'Opción eliminada exitosamente.');
        
        $this->assertDatabaseMissing('form_options', ['id' => $option->id]);
    }

    /**
     * Test: Activar/desactivar opción
     */
    public function test_admin_can_toggle_option_status()
    {
        $field = FormCategory::factory()->create();
        $option = FormOption::factory()->create([
            'category_id' => $field->id,
            'is_active' => false
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.options.toggle-status', [$field, $option]));

        $response->assertRedirect(route('admin.fields.options', $field));
        $response->assertSessionHas('success', 'Opción activada exitosamente.');
        
        $this->assertDatabaseHas('form_options', [
            'id' => $option->id,
            'is_active' => true
        ]);
    }

    /**
     * Test: Actualizar orden de opciones
     */
    public function test_admin_can_update_option_order()
    {
        $field = FormCategory::factory()->create();
        $option1 = FormOption::factory()->create(['category_id' => $field->id, 'order' => 1]);
        $option2 = FormOption::factory()->create(['category_id' => $field->id, 'order' => 2]);

        $orderData = [
            'options' => [
                ['id' => $option1->id, 'order' => 2],
                ['id' => $option2->id, 'order' => 1]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.fields.options.order', $field), $orderData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('form_options', [
            'id' => $option1->id,
            'order' => 2
        ]);
        $this->assertDatabaseHas('form_options', [
            'id' => $option2->id,
            'order' => 1
        ]);
    }

    /**
     * Test: Validación de orden de opciones
     */
    public function test_update_option_order_validation()
    {
        $field = FormCategory::factory()->create();

        // Test con datos inválidos
        $invalidData = [
            'options' => [
                ['id' => 999, 'order' => 1] // ID que no existe
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.fields.options.order', $field), $invalidData);

        $response->assertStatus(422);
    }

    /**
     * Test: Paginación de campos
     */
    public function test_fields_pagination()
    {
        // Crear más campos de los que caben en una página
        FormCategory::factory()->count(20)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.fields.index'));

        $response->assertStatus(200);
        $categories = $response->viewData('categories');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $categories);
        $this->assertCount(15, $categories->items()); // Primera página
    }

    /**
     * Test: Paginación de opciones
     */
    public function test_options_pagination()
    {
        $field = FormCategory::factory()->create();
        
        // Crear más opciones de las que caben en una página
        FormOption::factory()->count(20)->create(['category_id' => $field->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.fields.options', $field));

        $response->assertStatus(200);
        $options = $response->viewData('options');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $options);
        $this->assertCount(15, $options->items()); // Primera página
    }

    /**
     * Test: Ordenamiento de opciones
     */
    public function test_options_ordering()
    {
        $field = FormCategory::factory()->create();
        
        // Crear opciones con diferentes órdenes
        FormOption::factory()->create(['category_id' => $field->id, 'order' => 3, 'label' => 'Opción C']);
        FormOption::factory()->create(['category_id' => $field->id, 'order' => 1, 'label' => 'Opción A']);
        FormOption::factory()->create(['category_id' => $field->id, 'order' => 2, 'label' => 'Opción B']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.fields.options', $field));

        $response->assertStatus(200);
        $options = $response->viewData('options');
        
        // Debe estar ordenado por order ascendente
        $labels = $options->pluck('label')->toArray();
        $this->assertEquals(['Opción A', 'Opción B', 'Opción C'], $labels);
    }

    /**
     * Test: Acceso no autorizado para usuarios no admin
     */
    public function test_non_admin_cannot_access_fields()
    {
        $regularUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.fields.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: Usuario no autenticado no puede acceder
     */
    public function test_unauthenticated_user_cannot_access_fields()
    {
        $response = $this->get(route('admin.fields.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Crear opción con orden automático
     */
    public function test_create_option_with_automatic_order()
    {
        $field = FormCategory::factory()->create();
        
        // Crear algunas opciones existentes
        FormOption::factory()->create(['category_id' => $field->id, 'order' => 1]);
        FormOption::factory()->create(['category_id' => $field->id, 'order' => 2]);

        $optionData = [
            'value' => 'nueva-opcion',
            'label' => 'Nueva Opción',
            'is_active' => true
            // No especificar order, debe asignarse automáticamente
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.fields.options.store', $field), $optionData);

        $response->assertRedirect(route('admin.fields.options', $field));
        
        $this->assertDatabaseHas('form_options', [
            'category_id' => $field->id,
            'value' => 'nueva-opcion',
            'order' => 3 // Debe ser el siguiente orden disponible
        ]);
    }

    /**
     * Test: Relaciones entre campos y opciones
     */
    public function test_field_options_relationships()
    {
        $field = FormCategory::factory()->create();
        $option1 = FormOption::factory()->create(['category_id' => $field->id]);
        $option2 = FormOption::factory()->create(['category_id' => $field->id]);

        // Verificar que el campo tiene las opciones
        $this->assertCount(2, $field->formOptions);
        $this->assertTrue($field->formOptions->contains($option1));
        $this->assertTrue($field->formOptions->contains($option2));

        // Verificar que la opción pertenece al campo
        $this->assertEquals($field->id, $option1->category_id);
        $this->assertEquals($field->id, $option2->category_id);
    }
}
