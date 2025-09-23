<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear usuario administrador
        $this->adminUser = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@test.com',
            'password' => bcrypt('password123')
        ]);
    }

    /**
     * Test: Listar eventos como administrador
     */
    public function test_admin_can_view_events_index()
    {
        // Crear algunos eventos de prueba
        Event::factory()->count(5)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.events.index');
        $response->assertViewHas('events');
    }

    /**
     * Test: Crear evento exitosamente
     */
    public function test_admin_can_create_event()
    {
        $eventData = [
            'name' => 'Festival de Cine 2024',
            'city' => 'Bogotá',
            'year' => 2024
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), $eventData);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Evento creado exitosamente.');
        
        $this->assertDatabaseHas('events', $eventData);
    }

    /**
     * Test: Validaciones al crear evento
     */
    public function test_create_event_validation()
    {
        // Test con datos faltantes
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), []);

        $response->assertSessionHasErrors(['name', 'city', 'year']);

        // Test con año inválido
        $invalidData = [
            'name' => 'Test Event',
            'city' => 'Test City',
            'year' => 1999 // Año menor a 2000
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), $invalidData);

        $response->assertSessionHasErrors(['year']);

        // Test con año futuro muy lejano
        $invalidData['year'] = date('Y') + 15;
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), $invalidData);

        $response->assertSessionHasErrors(['year']);
    }

    /**
     * Test: Ver detalles de un evento
     */
    public function test_admin_can_view_event_details()
    {
        $event = Event::factory()->create();
        
        // Crear formularios asociados
        Form::factory()->count(3)->create(['event_id' => $event->id]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.show', $event));

        $response->assertStatus(200);
        $response->assertViewIs('admin.events.show');
        $response->assertViewHas('event');
    }

    /**
     * Test: Editar evento
     */
    public function test_admin_can_edit_event()
    {
        $event = Event::factory()->create([
            'name' => 'Evento Original',
            'city' => 'Ciudad Original',
            'year' => 2023
        ]);

        $updatedData = [
            'name' => 'Evento Actualizado',
            'city' => 'Ciudad Actualizada',
            'year' => 2024
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.events.update', $event), $updatedData);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Evento actualizado exitosamente.');
        
        $this->assertDatabaseHas('events', $updatedData);
    }

    /**
     * Test: Eliminar evento sin formularios asociados
     */
    public function test_admin_can_delete_event_without_forms()
    {
        $event = Event::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.events.destroy', $event));

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Evento eliminado exitosamente.');
        
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    /**
     * Test: No se puede eliminar evento con formularios asociados
     */
    public function test_cannot_delete_event_with_associated_forms()
    {
        $event = Event::factory()->create();
        Form::factory()->create(['event_id' => $event->id]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.events.destroy', $event));

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('error', 'No se puede eliminar el evento porque tiene formularios asociados. Elimina primero los formularios.');
        
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }

    /**
     * Test: Búsqueda de eventos
     */
    public function test_events_search_functionality()
    {
        // Crear eventos con diferentes nombres y ciudades
        Event::factory()->create(['name' => 'Festival de Cine', 'city' => 'Bogotá']);
        Event::factory()->create(['name' => 'Festival de Música', 'city' => 'Medellín']);
        Event::factory()->create(['name' => 'Festival de Arte', 'city' => 'Cali']);

        // Buscar por nombre
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index', ['search' => 'Cine']));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(1, $events->items());

        // Buscar por ciudad
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index', ['search' => 'Medellín']));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(1, $events->items());
    }

    /**
     * Test: Filtros de eventos por año y ciudad
     */
    public function test_events_filtering()
    {
        // Crear eventos con diferentes años y ciudades
        Event::factory()->create(['year' => 2023, 'city' => 'Bogotá']);
        Event::factory()->create(['year' => 2024, 'city' => 'Bogotá']);
        Event::factory()->create(['year' => 2023, 'city' => 'Medellín']);

        // Filtrar por año
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index', ['year' => 2023]));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(2, $events->items());

        // Filtrar por ciudad
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index', ['city' => 'Bogotá']));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertCount(2, $events->items());
    }

    /**
     * Test: Acceso no autorizado para usuarios no admin
     */
    public function test_non_admin_cannot_access_events()
    {
        $regularUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.events.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: Usuario no autenticado no puede acceder
     */
    public function test_unauthenticated_user_cannot_access_events()
    {
        $response = $this->get(route('admin.events.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Paginación de eventos
     */
    public function test_events_pagination()
    {
        // Crear más eventos de los que caben en una página (15 por defecto)
        Event::factory()->count(20)->create();

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index'));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $events);
        $this->assertCount(15, $events->items()); // Primera página
    }

    /**
     * Test: Ordenamiento de eventos
     */
    public function test_events_ordering()
    {
        // Crear eventos con diferentes años
        Event::factory()->create(['year' => 2022, 'name' => 'Evento A']);
        Event::factory()->create(['year' => 2024, 'name' => 'Evento B']);
        Event::factory()->create(['year' => 2023, 'name' => 'Evento C']);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index'));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        
        // Debe estar ordenado por año descendente, luego por nombre
        $eventYears = $events->pluck('year')->toArray();
        $this->assertEquals([2024, 2023, 2022], $eventYears);
    }
}
