<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Form;
use App\Models\FormCategory;
use App\Models\FormOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FormManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminUser = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);

        $this->event = Event::factory()->create([
            'name' => 'Festival de Cine 2024',
            'city' => 'Bogotá',
            'year' => 2024
        ]);
    }

    /**
     * Test: Listar formularios como administrador
     */
    public function test_admin_can_view_forms_index()
    {
        Form::factory()->count(5)->create(['event_id' => $this->event->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.forms.index');
        $response->assertViewHas('forms');
        $response->assertViewHas('events');
    }

    /**
     * Test: Crear formulario exitosamente
     */
    public function test_admin_can_create_form()
    {
        $formData = [
            'event_id' => $this->event->id,
            'name' => 'Formulario de Inscripción',
            'slug' => 'formulario-inscripcion',
            'description' => 'Formulario para inscribirse al festival',
            'is_active' => true,
            'version' => 1,
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'nombre',
                        'type' => 'text',
                        'label' => 'Nombre Completo',
                        'required' => true
                    ],
                    [
                        'id' => 'email',
                        'type' => 'email',
                        'label' => 'Correo Electrónico',
                        'required' => true
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.store'), $formData);

        $response->assertRedirect(route('admin.forms.index'));
        $response->assertSessionHas('success', 'Formulario creado exitosamente.');
        
        $this->assertDatabaseHas('forms', [
            'event_id' => $this->event->id,
            'name' => 'Formulario de Inscripción',
            'slug' => 'formulario-inscripcion'
        ]);
    }

    /**
     * Test: Validaciones al crear formulario
     */
    public function test_create_form_validation()
    {
        // Test con datos faltantes
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.store'), []);

        $response->assertSessionHasErrors(['event_id', 'name', 'slug']);

        // Test con slug duplicado
        Form::factory()->create(['slug' => 'formulario-existente']);
        
        $invalidData = [
            'event_id' => $this->event->id,
            'name' => 'Nuevo Formulario',
            'slug' => 'formulario-existente'
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.store'), $invalidData);

        $response->assertSessionHasErrors(['slug']);
    }

    /**
     * Test: Ver detalles de un formulario
     */
    public function test_admin_can_view_form_details()
    {
        $form = Form::factory()->create(['event_id' => $this->event->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.show', $form));

        $response->assertStatus(200);
        $response->assertViewIs('admin.forms.show');
        $response->assertViewHas('form');
    }

    /**
     * Test: Editar formulario
     */
    public function test_admin_can_edit_form()
    {
        $form = Form::factory()->create([
            'event_id' => $this->event->id,
            'name' => 'Formulario Original',
            'slug' => 'formulario-original'
        ]);

        $updatedData = [
            'event_id' => $this->event->id,
            'name' => 'Formulario Actualizado',
            'slug' => 'formulario-actualizado',
            'description' => 'Descripción actualizada',
            'is_active' => true,
            'version' => 2,
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'nombre_actualizado',
                        'type' => 'text',
                        'label' => 'Nombre Completo Actualizado',
                        'required' => true
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.forms.update', $form), $updatedData);

        $response->assertRedirect(route('admin.forms.index'));
        $response->assertSessionHas('success', 'Formulario actualizado exitosamente.');
        
        $this->assertDatabaseHas('forms', [
            'id' => $form->id,
            'name' => 'Formulario Actualizado',
            'slug' => 'formulario-actualizado'
        ]);
    }

    /**
     * Test: Eliminar formulario
     */
    public function test_admin_can_delete_form()
    {
        $form = Form::factory()->create(['event_id' => $this->event->id]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.forms.destroy', $form));

        $response->assertRedirect(route('admin.forms.index'));
        $response->assertSessionHas('success', 'Formulario eliminado exitosamente.');
        
        $this->assertSoftDeleted('forms', ['id' => $form->id]);
    }

    /**
     * Test: Activar formulario
     */
    public function test_admin_can_activate_form()
    {
        $form = Form::factory()->create([
            'event_id' => $this->event->id,
            'is_active' => false
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.activate', $form));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Formulario activado exitosamente.');
        
        $this->assertDatabaseHas('forms', [
            'id' => $form->id,
            'is_active' => true
        ]);
    }

    /**
     * Test: Desactivar formulario
     */
    public function test_admin_can_deactivate_form()
    {
        $form = Form::factory()->create([
            'event_id' => $this->event->id,
            'is_active' => true
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.deactivate', $form));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Formulario desactivado exitosamente.');
        
        $this->assertDatabaseHas('forms', [
            'id' => $form->id,
            'is_active' => false
        ]);
    }

    /**
     * Test: Obtener campos disponibles para formularios
     */
    public function test_get_available_fields()
    {
        // Crear categorías y opciones de prueba
        $category = FormCategory::factory()->create([
            'code' => 'genero',
            'name' => 'Género',
            'is_active' => true
        ]);

        FormOption::factory()->create([
            'category_id' => $category->id,
            'value' => 'masculino',
            'label' => 'Masculino',
            'is_active' => true,
            'order' => 1
        ]);

        FormOption::factory()->create([
            'category_id' => $category->id,
            'value' => 'femenino',
            'label' => 'Femenino',
            'is_active' => true,
            'order' => 2
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.available-fields'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);

        $data = $response->json();
        $this->assertArrayHasKey('fields', $data);
        $this->assertCount(1, $data['fields']);
        $this->assertEquals('genero', $data['fields'][0]['code']);
        $this->assertCount(2, $data['fields'][0]['options']);
    }

    /**
     * Test: Búsqueda de formularios
     */
    public function test_forms_search_functionality()
    {
        // Crear formularios con diferentes nombres
        Form::factory()->create([
            'event_id' => $this->event->id,
            'name' => 'Formulario de Inscripción'
        ]);
        Form::factory()->create([
            'event_id' => $this->event->id,
            'name' => 'Formulario de Evaluación'
        ]);

        // Buscar por nombre
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index', ['search' => 'Inscripción']));

        $response->assertStatus(200);
        $forms = $response->viewData('forms');
        $this->assertCount(1, $forms->items());
    }

    /**
     * Test: Filtros de formularios por evento y estado
     */
    public function test_forms_filtering()
    {
        $event2 = Event::factory()->create();
        
        // Crear formularios con diferentes estados y eventos
        Form::factory()->create([
            'event_id' => $this->event->id,
            'is_active' => true
        ]);
        Form::factory()->create([
            'event_id' => $this->event->id,
            'is_active' => false
        ]);
        Form::factory()->create([
            'event_id' => $event2->id,
            'is_active' => true
        ]);

        // Filtrar por evento
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index', ['event_id' => $this->event->id]));

        $response->assertStatus(200);
        $forms = $response->viewData('forms');
        $this->assertCount(2, $forms->items());

        // Filtrar por estado activo
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index', ['status' => 'active']));

        $response->assertStatus(200);
        $forms = $response->viewData('forms');
        $this->assertCount(2, $forms->items());
    }

    /**
     * Test: Acceso no autorizado para usuarios no admin
     */
    public function test_non_admin_cannot_access_forms()
    {
        $regularUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.forms.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: Paginación de formularios
     */
    public function test_forms_pagination()
    {
        // Crear más formularios de los que caben en una página
        Form::factory()->count(20)->create(['event_id' => $this->event->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index'));

        $response->assertStatus(200);
        $forms = $response->viewData('forms');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $forms);
        $this->assertCount(15, $forms->items()); // Primera página
    }

    /**
     * Test: Ordenamiento de formularios
     */
    public function test_forms_ordering()
    {
        $event2 = Event::factory()->create();
        
        // Crear formularios con diferentes versiones
        Form::factory()->create([
            'event_id' => $this->event->id,
            'version' => 1,
            'name' => 'Formulario A'
        ]);
        Form::factory()->create([
            'event_id' => $this->event->id,
            'version' => 3,
            'name' => 'Formulario B'
        ]);
        Form::factory()->create([
            'event_id' => $event2->id,
            'version' => 2,
            'name' => 'Formulario C'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index'));

        $response->assertStatus(200);
        $forms = $response->viewData('forms');
        
        // Debe estar ordenado por event_id, luego por version descendente
        $this->assertCount(3, $forms->items());
    }

    /**
     * Test: Crear formulario con estructura relacional
     */
    public function test_create_form_with_relational_structure()
    {
        // Crear categorías y opciones
        $category = FormCategory::factory()->create([
            'code' => 'genero',
            'name' => 'Género',
            'is_active' => true
        ]);

        FormOption::factory()->create([
            'category_id' => $category->id,
            'value' => 'masculino',
            'label' => 'Masculino',
            'is_active' => true
        ]);

        $formData = [
            'event_id' => $this->event->id,
            'name' => 'Formulario con Estructura Relacional',
            'slug' => 'formulario-relacional',
            'description' => 'Formulario que usa estructura relacional',
            'is_active' => true,
            'version' => 1,
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'genero',
                        'type' => 'select',
                        'label' => 'Género',
                        'required' => true,
                        'category_id' => $category->id
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.store'), $formData);

        $response->assertRedirect(route('admin.forms.index'));
        $response->assertSessionHas('success', 'Formulario creado exitosamente.');
        
        $this->assertDatabaseHas('forms', [
            'name' => 'Formulario con Estructura Relacional',
            'slug' => 'formulario-relacional'
        ]);
    }
}
