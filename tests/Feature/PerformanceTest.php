<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Form;
use App\Models\FormCategory;
use App\Models\FormOption;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceTest extends TestCase
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
     * Test: Rendimiento de listado de eventos con muchos registros
     */
    public function test_events_index_performance_with_large_dataset()
    {
        // Crear 1000 eventos para probar rendimiento
        Event::factory()->count(1000)->create();

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index'));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // El tiempo de ejecución debe ser menor a 2 segundos
        $this->assertLessThan(2.0, $executionTime, 
            "La carga de eventos tardó {$executionTime} segundos, debe ser menor a 2 segundos");
        
        // Verificar que se está usando paginación
        $events = $response->viewData('events');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $events);
        $this->assertCount(15, $events->items()); // Primera página
    }

    /**
     * Test: Rendimiento de búsqueda en eventos
     */
    public function test_events_search_performance()
    {
        // Crear eventos con diferentes nombres
        Event::factory()->count(500)->create();
        Event::factory()->count(10)->create(['name' => 'Festival de Cine']);
        Event::factory()->count(10)->create(['city' => 'Bogotá']);

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.index', ['search' => 'Festival de Cine']));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // El tiempo de ejecución debe ser menor a 1 segundo
        $this->assertLessThan(1.0, $executionTime, 
            "La búsqueda de eventos tardó {$executionTime} segundos, debe ser menor a 1 segundo");
        
        $events = $response->viewData('events');
        $this->assertCount(10, $events->items());
    }

    /**
     * Test: Rendimiento de listado de formularios con muchos registros
     */
    public function test_forms_index_performance_with_large_dataset()
    {
        // Crear eventos y formularios
        $events = Event::factory()->count(100)->create();
        
        foreach ($events as $event) {
            Form::factory()->count(5)->create(['event_id' => $event->id]);
        }

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index'));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // El tiempo de ejecución debe ser menor a 2 segundos
        $this->assertLessThan(2.0, $executionTime, 
            "La carga de formularios tardó {$executionTime} segundos, debe ser menor a 2 segundos");
        
        // Verificar que se está usando paginación
        $forms = $response->viewData('forms');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $forms);
        $this->assertCount(15, $forms->items()); // Primera página
    }

    /**
     * Test: Rendimiento de listado de envíos con muchos registros
     */
    public function test_submissions_index_performance_with_large_dataset()
    {
        // Crear eventos, formularios, participantes y envíos
        $events = Event::factory()->count(50)->create();
        
        foreach ($events as $event) {
            $forms = Form::factory()->count(3)->create(['event_id' => $event->id]);
            
            foreach ($forms as $form) {
                $participants = Participant::factory()->count(20)->create();
                
                foreach ($participants as $participant) {
                    FormSubmission::factory()->create([
                        'form_id' => $form->id,
                        'participant_id' => $participant->id
                    ]);
                }
            }
        }

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index'));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // El tiempo de ejecución debe ser menor a 3 segundos
        $this->assertLessThan(3.0, $executionTime, 
            "La carga de envíos tardó {$executionTime} segundos, debe ser menor a 3 segundos");
        
        // Verificar que se está usando paginación
        $submissions = $response->viewData('submissions');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $submissions);
        $this->assertCount(15, $submissions->items()); // Primera página
    }

    /**
     * Test: Rendimiento de envío de formulario con muchos campos
     */
    public function test_form_submission_performance_with_many_fields()
    {
        // Crear formulario con muchos campos
        $event = Event::factory()->create();
        $form = Form::factory()->create([
            'event_id' => $event->id,
            'slug' => 'formulario-complejo',
            'is_active' => true,
            'schema_json' => [
                'fields' => array_map(function($i) {
                    return [
                        'id' => "campo_{$i}",
                        'type' => 'text',
                        'label' => "Campo {$i}",
                        'required' => $i % 2 === 0
                    ];
                }, range(1, 50)) // 50 campos
            ]
        ]);

        // Crear datos de envío
        $submissionData = [];
        for ($i = 1; $i <= 50; $i++) {
            $submissionData["campo_{$i}"] = "Valor del campo {$i}";
        }

        $startTime = microtime(true);
        
        $response = $this->post(route('public.forms.slug.submit', $form->slug), $submissionData);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // El tiempo de ejecución debe ser menor a 1 segundo
        $this->assertLessThan(1.0, $executionTime, 
            "El envío del formulario tardó {$executionTime} segundos, debe ser menor a 1 segundo");
    }

    /**
     * Test: Rendimiento de creación masiva de eventos
     */
    public function test_bulk_event_creation_performance()
    {
        $startTime = microtime(true);
        
        // Crear 100 eventos de una vez
        $events = [];
        for ($i = 1; $i <= 100; $i++) {
            $events[] = [
                'name' => "Evento {$i}",
                'city' => "Ciudad {$i}",
                'year' => 2024,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        Event::insert($events);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verificar que se crearon todos los eventos
        $this->assertEquals(100, Event::count());
        
        // El tiempo de ejecución debe ser menor a 2 segundos
        $this->assertLessThan(2.0, $executionTime, 
            "La creación masiva de eventos tardó {$executionTime} segundos, debe ser menor a 2 segundos");
    }

    /**
     * Test: Rendimiento de consultas con relaciones complejas
     */
    public function test_complex_relationships_query_performance()
    {
        // Crear datos complejos
        $events = Event::factory()->count(20)->create();
        
        foreach ($events as $event) {
            $forms = Form::factory()->count(5)->create(['event_id' => $event->id]);
            
            foreach ($forms as $form) {
                $participants = Participant::factory()->count(10)->create();
                
                foreach ($participants as $participant) {
                    FormSubmission::factory()->create([
                        'form_id' => $form->id,
                        'participant_id' => $participant->id
                    ]);
                }
            }
        }

        $startTime = microtime(true);
        
        // Consulta compleja con múltiples relaciones
        $eventsWithStats = Event::with([
            'forms' => function($query) {
                $query->withCount('formSubmissions');
            }
        ])->get();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verificar que se obtuvieron los datos correctamente
        $this->assertCount(20, $eventsWithStats);
        
        foreach ($eventsWithStats as $event) {
            $this->assertCount(5, $event->forms);
            foreach ($event->forms as $form) {
                $this->assertEquals(10, $form->form_submissions_count);
            }
        }
        
        // El tiempo de ejecución debe ser menor a 2 segundos
        $this->assertLessThan(2.0, $executionTime, 
            "La consulta compleja tardó {$executionTime} segundos, debe ser menor a 2 segundos");
    }

    /**
     * Test: Rendimiento de filtros y búsquedas complejas
     */
    public function test_complex_filtering_performance()
    {
        // Crear datos de prueba
        $events = Event::factory()->count(100)->create();
        
        foreach ($events as $event) {
            Form::factory()->count(3)->create(['event_id' => $event->id]);
        }

        $startTime = microtime(true);
        
        // Aplicar múltiples filtros
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index', [
                'search' => 'Test',
                'event_id' => $events->first()->id,
                'status' => 'active'
            ]));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // El tiempo de ejecución debe ser menor a 1 segundo
        $this->assertLessThan(1.0, $executionTime, 
            "Los filtros complejos tardaron {$executionTime} segundos, debe ser menor a 1 segundo");
    }

    /**
     * Test: Rendimiento de consultas con agregaciones
     */
    public function test_aggregation_queries_performance()
    {
        // Crear datos de prueba
        $events = Event::factory()->count(50)->create();
        
        foreach ($events as $event) {
            $forms = Form::factory()->count(4)->create(['event_id' => $event->id]);
            
            foreach ($forms as $form) {
                $participants = Participant::factory()->count(15)->create();
                
                foreach ($participants as $participant) {
                    FormSubmission::factory()->create([
                        'form_id' => $form->id,
                        'participant_id' => $participant->id
                    ]);
                }
            }
        }

        $startTime = microtime(true);
        
        // Consultas con agregaciones
        $stats = [
            'total_events' => Event::count(),
            'total_forms' => Form::count(),
            'total_participants' => Participant::count(),
            'total_submissions' => FormSubmission::count(),
            'submissions_per_event' => Event::withCount('forms')->get()->sum('forms_count'),
            'average_submissions_per_form' => Form::withCount('formSubmissions')->get()->avg('form_submissions_count')
        ];

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verificar que se obtuvieron las estadísticas correctamente
        $this->assertEquals(50, $stats['total_events']);
        $this->assertEquals(200, $stats['total_forms']); // 50 eventos * 4 formularios
        $this->assertEquals(3000, $stats['total_participants']); // 50 * 4 * 15
        $this->assertEquals(3000, $stats['total_submissions']);
        
        // El tiempo de ejecución debe ser menor a 2 segundos
        $this->assertLessThan(2.0, $executionTime, 
            "Las consultas de agregación tardaron {$executionTime} segundos, debe ser menor a 2 segundos");
    }

    /**
     * Test: Rendimiento de memoria con grandes volúmenes de datos
     */
    public function test_memory_usage_with_large_datasets()
    {
        $initialMemory = memory_get_usage();
        
        // Crear un gran volumen de datos
        $events = Event::factory()->count(100)->create();
        
        foreach ($events as $event) {
            $forms = Form::factory()->count(10)->create(['event_id' => $event->id]);
            
            foreach ($forms as $form) {
                $participants = Participant::factory()->count(20)->create();
                
                foreach ($participants as $participant) {
                    FormSubmission::factory()->create([
                        'form_id' => $form->id,
                        'participant_id' => $participant->id
                    ]);
                }
            }
        }

        $finalMemory = memory_get_usage();
        $memoryUsed = ($finalMemory - $initialMemory) / 1024 / 1024; // MB

        // Verificar que se crearon todos los datos
        $this->assertEquals(100, Event::count());
        $this->assertEquals(1000, Form::count());
        $this->assertEquals(20000, Participant::count());
        $this->assertEquals(20000, FormSubmission::count());
        
        // El uso de memoria debe ser razonable (menos de 100MB para este test)
        $this->assertLessThan(100, $memoryUsed, 
            "El uso de memoria fue {$memoryUsed}MB, debe ser menor a 100MB");
    }

    /**
     * Test: Rendimiento de transacciones de base de datos
     */
    public function test_database_transaction_performance()
    {
        $startTime = microtime(true);
        
        // Simular una transacción compleja
        DB::transaction(function () {
            $event = Event::factory()->create();
            $form = Form::factory()->create(['event_id' => $event->id]);
            
            $participants = Participant::factory()->count(100)->create();
            
            foreach ($participants as $participant) {
                FormSubmission::factory()->create([
                    'form_id' => $form->id,
                    'participant_id' => $participant->id
                ]);
            }
        });

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Verificar que se crearon los datos
        $this->assertEquals(1, Event::count());
        $this->assertEquals(1, Form::count());
        $this->assertEquals(100, Participant::count());
        $this->assertEquals(100, FormSubmission::count());
        
        // El tiempo de ejecución debe ser menor a 2 segundos
        $this->assertLessThan(2.0, $executionTime, 
            "La transacción tardó {$executionTime} segundos, debe ser menor a 2 segundos");
    }

    /**
     * Test: Rendimiento de consultas con índices
     */
    public function test_indexed_queries_performance()
    {
        // Crear datos de prueba
        Event::factory()->count(1000)->create();
        Form::factory()->count(5000)->create();
        Participant::factory()->count(10000)->create();
        FormSubmission::factory()->count(10000)->create();

        $startTime = microtime(true);
        
        // Consultas que deberían usar índices
        $event = Event::where('name', 'like', '%Test%')->first();
        $form = Form::where('slug', 'test-slug')->first();
        $participant = Participant::where('email', 'test@example.com')->first();
        $submission = FormSubmission::where('form_id', 1)->first();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // El tiempo de ejecución debe ser menor a 0.5 segundos
        $this->assertLessThan(0.5, $executionTime, 
            "Las consultas indexadas tardaron {$executionTime} segundos, debe ser menor a 0.5 segundos");
    }
}
