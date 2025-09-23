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
use Tests\TestCase;

class EndToEndIntegrationTest extends TestCase
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
     * Test: Flujo completo desde crear evento hasta recibir envíos
     * Este test simula el flujo completo de un administrador creando
     * un evento, formulario, campos y recibiendo envíos de usuarios
     */
    public function test_complete_workflow_from_event_creation_to_submissions()
    {
        // PASO 1: Crear evento
        $eventData = [
            'name' => 'Festival de Cine Internacional 2024',
            'city' => 'Bogotá',
            'year' => 2024
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), $eventData);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Evento creado exitosamente.');
        
        $event = Event::where('name', 'Festival de Cine Internacional 2024')->first();
        $this->assertNotNull($event);

        // PASO 2: Crear campos (categorías) para el formulario
        $generoField = FormCategory::factory()->create([
            'code' => 'genero',
            'name' => 'Género',
            'description' => 'Selección de género del participante',
            'is_active' => true
        ]);

        $tipoParticipanteField = FormCategory::factory()->create([
            'code' => 'tipo_participante',
            'name' => 'Tipo de Participante',
            'description' => 'Tipo de participación en el festival',
            'is_active' => true
        ]);

        // PASO 3: Crear opciones para los campos
        FormOption::factory()->create([
            'category_id' => $generoField->id,
            'value' => 'masculino',
            'label' => 'Masculino',
            'is_active' => true,
            'order' => 1
        ]);

        FormOption::factory()->create([
            'category_id' => $generoField->id,
            'value' => 'femenino',
            'label' => 'Femenino',
            'is_active' => true,
            'order' => 2
        ]);

        FormOption::factory()->create([
            'category_id' => $tipoParticipanteField->id,
            'value' => 'director',
            'label' => 'Director',
            'is_active' => true,
            'order' => 1
        ]);

        FormOption::factory()->create([
            'category_id' => $tipoParticipanteField->id,
            'value' => 'productor',
            'label' => 'Productor',
            'is_active' => true,
            'order' => 2
        ]);

        FormOption::factory()->create([
            'category_id' => $tipoParticipanteField->id,
            'value' => 'actor',
            'label' => 'Actor/Actriz',
            'is_active' => true,
            'order' => 3
        ]);

        // PASO 4: Crear formulario
        $formData = [
            'event_id' => $event->id,
            'name' => 'Formulario de Inscripción Festival de Cine',
            'slug' => 'inscripcion-festival-cine-2024',
            'description' => 'Formulario para inscribirse al Festival de Cine Internacional 2024',
            'is_active' => true,
            'version' => 1,
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'nombre_completo',
                        'type' => 'text',
                        'label' => 'Nombre Completo',
                        'required' => true,
                        'placeholder' => 'Ingrese su nombre completo'
                    ],
                    [
                        'id' => 'email',
                        'type' => 'email',
                        'label' => 'Correo Electrónico',
                        'required' => true,
                        'placeholder' => 'ejemplo@correo.com'
                    ],
                    [
                        'id' => 'telefono',
                        'type' => 'text',
                        'label' => 'Teléfono',
                        'required' => true,
                        'placeholder' => '+57 300 123 4567'
                    ],
                    [
                        'id' => 'genero',
                        'type' => 'select',
                        'label' => 'Género',
                        'required' => true,
                        'category_id' => $generoField->id
                    ],
                    [
                        'id' => 'tipo_participante',
                        'type' => 'select',
                        'label' => 'Tipo de Participante',
                        'required' => true,
                        'category_id' => $tipoParticipanteField->id
                    ],
                    [
                        'id' => 'experiencia',
                        'type' => 'textarea',
                        'label' => 'Experiencia en Cine',
                        'required' => false,
                        'placeholder' => 'Describa su experiencia en el mundo del cine...'
                    ]
                ]
            ]
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.store'), $formData);

        $response->assertRedirect(route('admin.forms.index'));
        $response->assertSessionHas('success', 'Formulario creado exitosamente.');
        
        $form = Form::where('slug', 'inscripcion-festival-cine-2024')->first();
        $this->assertNotNull($form);

        // PASO 5: Verificar que el formulario está activo y accesible públicamente
        $response = $this->get(route('public.forms.slug.show', $form->slug));
        $response->assertStatus(200);
        $response->assertViewHas('form');

        // PASO 6: Simular múltiples envíos de formulario
        $submissions = [
            [
                'nombre_completo' => 'María García López',
                'email' => 'maria.garcia@email.com',
                'telefono' => '+57 300 111 2222',
                'genero' => 'femenino',
                'tipo_participante' => 'director',
                'experiencia' => 'Directora con 10 años de experiencia en documentales'
            ],
            [
                'nombre_completo' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@email.com',
                'telefono' => '+57 300 333 4444',
                'genero' => 'masculino',
                'tipo_participante' => 'productor',
                'experiencia' => 'Productor ejecutivo de largometrajes'
            ],
            [
                'nombre_completo' => 'Ana Martínez',
                'email' => 'ana.martinez@email.com',
                'telefono' => '+57 300 555 6666',
                'genero' => 'femenino',
                'tipo_participante' => 'actor',
                'experiencia' => 'Actriz de teatro y cine independiente'
            ]
        ];

        foreach ($submissions as $submissionData) {
            $response = $this->post(route('public.forms.slug.submit', $form->slug), $submissionData);
            $response->assertRedirect();
            $response->assertSessionHas('success');
        }

        // PASO 7: Verificar que se crearon los participantes
        $this->assertEquals(3, Participant::count());
        $this->assertDatabaseHas('participants', ['email' => 'maria.garcia@email.com']);
        $this->assertDatabaseHas('participants', ['email' => 'carlos.rodriguez@email.com']);
        $this->assertDatabaseHas('participants', ['email' => 'ana.martinez@email.com']);

        // PASO 8: Verificar que se crearon los envíos
        $this->assertEquals(3, FormSubmission::count());
        $this->assertEquals(3, $form->formSubmissions()->count());

        // PASO 9: Verificar que el administrador puede ver los envíos
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index'));

        $response->assertStatus(200);
        $submissions = $response->viewData('submissions');
        $this->assertCount(3, $submissions->items());

        // PASO 10: Verificar estadísticas del evento
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.events.show', $event));

        $response->assertStatus(200);
        $eventData = $response->viewData('event');
        $this->assertCount(1, $eventData->forms);
        $this->assertEquals(3, $eventData->forms->first()->form_submissions_count);
    }

    /**
     * Test: Flujo completo con formulario condicional
     * Este test simula un formulario con campos que aparecen/desaparecen
     * según las respuestas del usuario
     */
    public function test_complete_workflow_with_conditional_form()
    {
        // Crear evento
        $event = Event::factory()->create([
            'name' => 'Conferencia de Tecnología 2024',
            'city' => 'Medellín',
            'year' => 2024
        ]);

        // Crear campos condicionales
        $tipoAsistenteField = FormCategory::factory()->create([
            'code' => 'tipo_asistente',
            'name' => 'Tipo de Asistente',
            'is_active' => true
        ]);

        FormOption::factory()->create([
            'category_id' => $tipoAsistenteField->id,
            'value' => 'estudiante',
            'label' => 'Estudiante',
            'is_active' => true,
            'order' => 1
        ]);

        FormOption::factory()->create([
            'category_id' => $tipoAsistenteField->id,
            'value' => 'profesional',
            'label' => 'Profesional',
            'is_active' => true,
            'order' => 2
        ]);

        // Crear formulario con campos condicionales
        $form = Form::factory()->create([
            'event_id' => $event->id,
            'name' => 'Registro Conferencia Tecnología',
            'slug' => 'registro-conferencia-tecnologia',
            'is_active' => true,
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
                    ],
                    [
                        'id' => 'tipo_asistente',
                        'type' => 'select',
                        'label' => 'Tipo de Asistente',
                        'required' => true,
                        'category_id' => $tipoAsistenteField->id
                    ],
                    [
                        'id' => 'universidad',
                        'type' => 'text',
                        'label' => 'Universidad',
                        'required' => false,
                        'conditional' => [
                            'field' => 'tipo_asistente',
                            'value' => 'estudiante'
                        ]
                    ],
                    [
                        'id' => 'carrera',
                        'type' => 'text',
                        'label' => 'Carrera',
                        'required' => false,
                        'conditional' => [
                            'field' => 'tipo_asistente',
                            'value' => 'estudiante'
                        ]
                    ],
                    [
                        'id' => 'empresa',
                        'type' => 'text',
                        'label' => 'Empresa',
                        'required' => false,
                        'conditional' => [
                            'field' => 'tipo_asistente',
                            'value' => 'profesional'
                        ]
                    ],
                    [
                        'id' => 'cargo',
                        'type' => 'text',
                        'label' => 'Cargo',
                        'required' => false,
                        'conditional' => [
                            'field' => 'tipo_asistente',
                            'value' => 'profesional'
                        ]
                    ]
                ]
            ]
        ]);

        // Envío como estudiante
        $studentSubmission = [
            'nombre' => 'Pedro Estudiante',
            'email' => 'pedro@estudiante.com',
            'tipo_asistente' => 'estudiante',
            'universidad' => 'Universidad Nacional',
            'carrera' => 'Ingeniería de Sistemas'
        ];

        $response = $this->post(route('public.forms.slug.submit', $form->slug), $studentSubmission);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Envío como profesional
        $professionalSubmission = [
            'nombre' => 'Laura Profesional',
            'email' => 'laura@profesional.com',
            'tipo_asistente' => 'profesional',
            'empresa' => 'Tech Solutions S.A.S.',
            'cargo' => 'Desarrolladora Senior'
        ];

        $response = $this->post(route('public.forms.slug.submit', $form->slug), $professionalSubmission);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verificar envíos
        $this->assertEquals(2, FormSubmission::count());
        
        $studentParticipant = Participant::where('email', 'pedro@estudiante.com')->first();
        $studentSubmissionRecord = FormSubmission::where('participant_id', $studentParticipant->id)->first();
        
        $this->assertArrayHasKey('universidad', $studentSubmissionRecord->data_json);
        $this->assertArrayHasKey('carrera', $studentSubmissionRecord->data_json);
        $this->assertEquals('Universidad Nacional', $studentSubmissionRecord->data_json['universidad']);
        $this->assertEquals('Ingeniería de Sistemas', $studentSubmissionRecord->data_json['carrera']);

        $professionalParticipant = Participant::where('email', 'laura@profesional.com')->first();
        $professionalSubmissionRecord = FormSubmission::where('participant_id', $professionalParticipant->id)->first();
        
        $this->assertArrayHasKey('empresa', $professionalSubmissionRecord->data_json);
        $this->assertArrayHasKey('cargo', $professionalSubmissionRecord->data_json);
        $this->assertEquals('Tech Solutions S.A.S.', $professionalSubmissionRecord->data_json['empresa']);
        $this->assertEquals('Desarrolladora Senior', $professionalSubmissionRecord->data_json['cargo']);
    }

    /**
     * Test: Flujo completo con múltiples formularios por evento
     */
    public function test_complete_workflow_with_multiple_forms_per_event()
    {
        // Crear evento
        $event = Event::factory()->create([
            'name' => 'Festival de Arte 2024',
            'city' => 'Cali',
            'year' => 2024
        ]);

        // Crear múltiples formularios para el mismo evento
        $inscripcionForm = Form::factory()->create([
            'event_id' => $event->id,
            'name' => 'Inscripción General',
            'slug' => 'inscripcion-general-festival-arte',
            'is_active' => true,
            'version' => 1
        ]);

        $evaluacionForm = Form::factory()->create([
            'event_id' => $event->id,
            'name' => 'Evaluación de Obras',
            'slug' => 'evaluacion-obras-festival-arte',
            'is_active' => true,
            'version' => 1
        ]);

        $feedbackForm = Form::factory()->create([
            'event_id' => $event->id,
            'name' => 'Feedback del Evento',
            'slug' => 'feedback-festival-arte',
            'is_active' => true,
            'version' => 1
        ]);

        // Crear participantes
        $participant1 = Participant::factory()->create([
            'name' => 'Artista Uno',
            'email' => 'artista1@email.com'
        ]);

        $participant2 = Participant::factory()->create([
            'name' => 'Artista Dos',
            'email' => 'artista2@email.com'
        ]);

        // Envíos al formulario de inscripción
        FormSubmission::factory()->create([
            'form_id' => $inscripcionForm->id,
            'participant_id' => $participant1->id,
            'data_json' => [
                'nombre' => 'Artista Uno',
                'email' => 'artista1@email.com',
                'tipo_arte' => 'pintura'
            ]
        ]);

        FormSubmission::factory()->create([
            'form_id' => $inscripcionForm->id,
            'participant_id' => $participant2->id,
            'data_json' => [
                'nombre' => 'Artista Dos',
                'email' => 'artista2@email.com',
                'tipo_arte' => 'escultura'
            ]
        ]);

        // Envíos al formulario de evaluación
        FormSubmission::factory()->create([
            'form_id' => $evaluacionForm->id,
            'participant_id' => $participant1->id,
            'data_json' => [
                'obra' => 'Paisaje Urbano',
                'calificacion' => 9,
                'comentarios' => 'Excelente técnica'
            ]
        ]);

        // Envíos al formulario de feedback
        FormSubmission::factory()->create([
            'form_id' => $feedbackForm->id,
            'participant_id' => $participant1->id,
            'data_json' => [
                'satisfaccion' => 'muy_satisfecho',
                'sugerencias' => 'Más tiempo para networking'
            ]
        ]);

        FormSubmission::factory()->create([
            'form_id' => $feedbackForm->id,
            'participant_id' => $participant2->id,
            'data_json' => [
                'satisfaccion' => 'satisfecho',
                'sugerencias' => 'Mejorar la iluminación'
            ]
        ]);

        // Verificar estadísticas del evento
        $this->assertEquals(3, $event->forms()->count());
        $this->assertEquals(5, FormSubmission::count());
        $this->assertEquals(2, $inscripcionForm->formSubmissions()->count());
        $this->assertEquals(1, $evaluacionForm->formSubmissions()->count());
        $this->assertEquals(2, $feedbackForm->formSubmissions()->count());

        // Verificar que el administrador puede ver todos los formularios
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.forms.index', ['event_id' => $event->id]));

        $response->assertStatus(200);
        $forms = $response->viewData('forms');
        $this->assertCount(3, $forms->items());

        // Verificar que el administrador puede ver todos los envíos
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index'));

        $response->assertStatus(200);
        $submissions = $response->viewData('submissions');
        $this->assertCount(5, $submissions->items());
    }

    /**
     * Test: Flujo completo con desactivación y reactivación de formularios
     */
    public function test_complete_workflow_with_form_activation_deactivation()
    {
        // Crear evento y formulario
        $event = Event::factory()->create();
        $form = Form::factory()->create([
            'event_id' => $event->id,
            'is_active' => true
        ]);

        // Verificar que el formulario está activo
        $response = $this->get(route('public.forms.slug.show', $form->slug));
        $response->assertStatus(200);

        // Desactivar formulario
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.deactivate', $form));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Formulario desactivado exitosamente.');

        // Verificar que el formulario ya no es accesible
        $response = $this->get(route('public.forms.slug.show', $form->slug));
        $response->assertStatus(404);

        // Intentar enviar formulario desactivado
        $submissionData = [
            'nombre' => 'Test User',
            'email' => 'test@email.com'
        ];

        $response = $this->post(route('public.forms.slug.submit', $form->slug), $submissionData);
        $response->assertStatus(404);

        // Reactivar formulario
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.forms.activate', $form));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Formulario activado exitosamente.');

        // Verificar que el formulario es accesible nuevamente
        $response = $this->get(route('public.forms.slug.show', $form->slug));
        $response->assertStatus(200);

        // Enviar formulario reactivado
        $response = $this->post(route('public.forms.slug.submit', $form->slug), $submissionData);
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Test: Flujo completo con eliminación de datos
     */
    public function test_complete_workflow_with_data_deletion()
    {
        // Crear evento, formulario y envíos
        $event = Event::factory()->create();
        $form = Form::factory()->create(['event_id' => $event->id]);
        $participant = Participant::factory()->create();
        
        FormSubmission::factory()->create([
            'form_id' => $form->id,
            'participant_id' => $participant->id
        ]);

        // Verificar que existen los datos
        $this->assertDatabaseHas('events', ['id' => $event->id]);
        $this->assertDatabaseHas('forms', ['id' => $form->id]);
        $this->assertDatabaseHas('participants', ['id' => $participant->id]);
        $this->assertDatabaseHas('form_submissions', ['form_id' => $form->id]);

        // Eliminar formulario (soft delete)
        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.forms.destroy', $form));

        $response->assertRedirect(route('admin.forms.index'));
        $response->assertSessionHas('success', 'Formulario eliminado exitosamente.');

        // Verificar que el formulario fue eliminado (soft delete)
        $this->assertSoftDeleted('forms', ['id' => $form->id]);

        // Verificar que el evento y participante siguen existiendo
        $this->assertDatabaseHas('events', ['id' => $event->id]);
        $this->assertDatabaseHas('participants', ['id' => $participant->id]);

        // Verificar que el envío sigue existiendo
        $this->assertDatabaseHas('form_submissions', ['form_id' => $form->id]);
    }
}
