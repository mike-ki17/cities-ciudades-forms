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

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;
    protected Event $event;
    protected Form $form;
    protected FormCategory $field;
    protected FormOption $option1;
    protected FormOption $option2;

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

        $this->form = Form::factory()->create([
            'event_id' => $this->event->id,
            'name' => 'Formulario de Inscripción',
            'slug' => 'formulario-inscripcion',
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
                        'id' => 'genero',
                        'type' => 'select',
                        'label' => 'Género',
                        'required' => true
                    ]
                ]
            ]
        ]);

        // Crear campo y opciones para el formulario
        $this->field = FormCategory::factory()->create([
            'code' => 'genero',
            'name' => 'Género',
            'is_active' => true
        ]);

        $this->option1 = FormOption::factory()->create([
            'category_id' => $this->field->id,
            'value' => 'masculino',
            'label' => 'Masculino',
            'is_active' => true,
            'order' => 1
        ]);

        $this->option2 = FormOption::factory()->create([
            'category_id' => $this->field->id,
            'value' => 'femenino',
            'label' => 'Femenino',
            'is_active' => true,
            'order' => 2
        ]);
    }

    /**
     * Test: Acceso público a formulario por slug
     */
    public function test_public_can_access_form_by_slug()
    {
        $response = $this->get(route('public.forms.slug.show', $this->form->slug));

        $response->assertStatus(200);
        $response->assertViewIs('public.forms.show');
        $response->assertViewHas('form');
    }

    /**
     * Test: No se puede acceder a formulario inactivo
     */
    public function test_cannot_access_inactive_form()
    {
        $inactiveForm = Form::factory()->create([
            'event_id' => $this->event->id,
            'slug' => 'formulario-inactivo',
            'is_active' => false
        ]);

        $response = $this->get(route('public.forms.slug.show', $inactiveForm->slug));

        $response->assertStatus(404);
    }

    /**
     * Test: Envío exitoso de formulario
     */
    public function test_public_can_submit_form()
    {
        $submissionData = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'genero' => 'masculino'
        ];

        $response = $this->post(route('public.forms.slug.submit', $this->form->slug), $submissionData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verificar que se creó el participante
        $this->assertDatabaseHas('participants', [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com'
        ]);

        // Verificar que se creó el envío
        $participant = Participant::where('email', 'juan@example.com')->first();
        $this->assertDatabaseHas('form_submissions', [
            'form_id' => $this->form->id,
            'participant_id' => $participant->id
        ]);
    }

    /**
     * Test: Validaciones de envío de formulario
     */
    public function test_form_submission_validation()
    {
        // Test con datos faltantes
        $response = $this->post(route('public.forms.slug.submit', $this->form->slug), []);

        $response->assertSessionHasErrors(['nombre', 'email', 'genero']);

        // Test con email inválido
        $invalidData = [
            'nombre' => 'Juan Pérez',
            'email' => 'email-invalido',
            'genero' => 'masculino'
        ];

        $response = $this->post(route('public.forms.slug.submit', $this->form->slug), $invalidData);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test: No se puede enviar formulario inactivo
     */
    public function test_cannot_submit_inactive_form()
    {
        $inactiveForm = Form::factory()->create([
            'event_id' => $this->event->id,
            'slug' => 'formulario-inactivo',
            'is_active' => false
        ]);

        $submissionData = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'genero' => 'masculino'
        ];

        $response = $this->post(route('public.forms.slug.submit', $inactiveForm->slug), $submissionData);

        $response->assertStatus(404);
    }

    /**
     * Test: Envío con participante existente
     */
    public function test_submit_form_with_existing_participant()
    {
        // Crear participante existente
        $existingParticipant = Participant::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com'
        ]);

        $submissionData = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'genero' => 'masculino'
        ];

        $response = $this->post(route('public.forms.slug.submit', $this->form->slug), $submissionData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verificar que se usó el participante existente
        $this->assertDatabaseHas('form_submissions', [
            'form_id' => $this->form->id,
            'participant_id' => $existingParticipant->id
        ]);

        // Verificar que no se creó un participante duplicado
        $this->assertEquals(1, Participant::where('email', 'juan@example.com')->count());
    }

    /**
     * Test: Administrador puede ver envíos
     */
    public function test_admin_can_view_submissions()
    {
        // Crear algunos envíos
        $participant1 = Participant::factory()->create();
        $participant2 = Participant::factory()->create();
        
        FormSubmission::factory()->create([
            'form_id' => $this->form->id,
            'participant_id' => $participant1->id
        ]);
        FormSubmission::factory()->create([
            'form_id' => $this->form->id,
            'participant_id' => $participant2->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.submissions.index');
    }

    /**
     * Test: Filtros de envíos por formulario
     */
    public function test_submissions_filtering_by_form()
    {
        $form2 = Form::factory()->create(['event_id' => $this->event->id]);
        
        $participant1 = Participant::factory()->create();
        $participant2 = Participant::factory()->create();
        
        FormSubmission::factory()->create([
            'form_id' => $this->form->id,
            'participant_id' => $participant1->id
        ]);
        FormSubmission::factory()->create([
            'form_id' => $form2->id,
            'participant_id' => $participant2->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index', ['form_id' => $this->form->id]));

        $response->assertStatus(200);
        $submissions = $response->viewData('submissions');
        $this->assertCount(1, $submissions->items());
    }

    /**
     * Test: Búsqueda de envíos
     */
    public function test_submissions_search()
    {
        $participant1 = Participant::factory()->create(['name' => 'Juan Pérez']);
        $participant2 = Participant::factory()->create(['name' => 'María García']);
        
        FormSubmission::factory()->create([
            'form_id' => $this->form->id,
            'participant_id' => $participant1->id
        ]);
        FormSubmission::factory()->create([
            'form_id' => $this->form->id,
            'participant_id' => $participant2->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index', ['search' => 'Juan']));

        $response->assertStatus(200);
        $submissions = $response->viewData('submissions');
        $this->assertCount(1, $submissions->items());
    }

    /**
     * Test: Paginación de envíos
     */
    public function test_submissions_pagination()
    {
        // Crear más envíos de los que caben en una página
        $participants = Participant::factory()->count(20)->create();
        
        foreach ($participants as $participant) {
            FormSubmission::factory()->create([
                'form_id' => $this->form->id,
                'participant_id' => $participant->id
            ]);
        }

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.submissions.index'));

        $response->assertStatus(200);
        $submissions = $response->viewData('submissions');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $submissions);
        $this->assertCount(15, $submissions->items()); // Primera página
    }

    /**
     * Test: Datos de envío se almacenan correctamente
     */
    public function test_submission_data_storage()
    {
        $submissionData = [
            'nombre' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'genero' => 'masculino',
            'telefono' => '1234567890',
            'comentarios' => 'Comentarios adicionales'
        ];

        $response = $this->post(route('public.forms.slug.submit', $this->form->slug), $submissionData);

        $response->assertRedirect();
        
        $participant = Participant::where('email', 'juan@example.com')->first();
        $submission = FormSubmission::where('participant_id', $participant->id)->first();
        
        // Verificar que los datos se almacenaron en el JSON
        $this->assertNotNull($submission->data_json);
        $this->assertArrayHasKey('nombre', $submission->data_json);
        $this->assertArrayHasKey('email', $submission->data_json);
        $this->assertArrayHasKey('genero', $submission->data_json);
        $this->assertEquals('Juan Pérez', $submission->data_json['nombre']);
        $this->assertEquals('juan@example.com', $submission->data_json['email']);
        $this->assertEquals('masculino', $submission->data_json['genero']);
    }

    /**
     * Test: Envío con campos condicionales
     */
    public function test_submission_with_conditional_fields()
    {
        // Crear formulario con campos condicionales
        $conditionalForm = Form::factory()->create([
            'event_id' => $this->event->id,
            'slug' => 'formulario-condicional',
            'is_active' => true,
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'tipo_participante',
                        'type' => 'select',
                        'label' => 'Tipo de Participante',
                        'required' => true,
                        'options' => [
                            ['value' => 'estudiante', 'label' => 'Estudiante'],
                            ['value' => 'profesional', 'label' => 'Profesional']
                        ]
                    ],
                    [
                        'id' => 'universidad',
                        'type' => 'text',
                        'label' => 'Universidad',
                        'required' => false,
                        'conditional' => [
                            'field' => 'tipo_participante',
                            'value' => 'estudiante'
                        ]
                    ],
                    [
                        'id' => 'empresa',
                        'type' => 'text',
                        'label' => 'Empresa',
                        'required' => false,
                        'conditional' => [
                            'field' => 'tipo_participante',
                            'value' => 'profesional'
                        ]
                    ]
                ]
            ]
        ]);

        // Envío como estudiante
        $studentData = [
            'tipo_participante' => 'estudiante',
            'universidad' => 'Universidad Nacional',
            'nombre' => 'Juan Estudiante',
            'email' => 'juan@estudiante.com'
        ];

        $response = $this->post(route('public.forms.slug.submit', $conditionalForm->slug), $studentData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Envío como profesional
        $professionalData = [
            'tipo_participante' => 'profesional',
            'empresa' => 'Empresa ABC',
            'nombre' => 'María Profesional',
            'email' => 'maria@profesional.com'
        ];

        $response = $this->post(route('public.forms.slug.submit', $conditionalForm->slug), $professionalData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Test: Envío con validaciones personalizadas
     */
    public function test_submission_with_custom_validations()
    {
        // Crear formulario con validaciones personalizadas
        $customForm = Form::factory()->create([
            'event_id' => $this->event->id,
            'slug' => 'formulario-validaciones',
            'is_active' => true,
            'schema_json' => [
                'fields' => [
                    [
                        'id' => 'edad',
                        'type' => 'number',
                        'label' => 'Edad',
                        'required' => true,
                        'validation' => [
                            'min' => 18,
                            'max' => 65
                        ]
                    ],
                    [
                        'id' => 'telefono',
                        'type' => 'text',
                        'label' => 'Teléfono',
                        'required' => true,
                        'validation' => [
                            'pattern' => '^[0-9]{10}$'
                        ]
                    ]
                ]
            ]
        ]);

        // Test con edad inválida
        $invalidAgeData = [
            'edad' => 16, // Menor a 18
            'telefono' => '1234567890',
            'nombre' => 'Juan Menor',
            'email' => 'juan@menor.com'
        ];

        $response = $this->post(route('public.forms.slug.submit', $customForm->slug), $invalidAgeData);

        $response->assertSessionHasErrors(['edad']);

        // Test con teléfono inválido
        $invalidPhoneData = [
            'edad' => 25,
            'telefono' => '123', // Muy corto
            'nombre' => 'Juan Telefono',
            'email' => 'juan@telefono.com'
        ];

        $response = $this->post(route('public.forms.slug.submit', $customForm->slug), $invalidPhoneData);

        $response->assertSessionHasErrors(['telefono']);
    }

    /**
     * Test: Acceso no autorizado para usuarios no admin
     */
    public function test_non_admin_cannot_access_submissions()
    {
        $regularUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($regularUser)
            ->get(route('admin.submissions.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: Relaciones entre modelos en envíos
     */
    public function test_submission_model_relationships()
    {
        $participant = Participant::factory()->create();
        $submission = FormSubmission::factory()->create([
            'form_id' => $this->form->id,
            'participant_id' => $participant->id
        ]);

        // Verificar relaciones
        $this->assertEquals($this->form->id, $submission->form->id);
        $this->assertEquals($participant->id, $submission->participant->id);
        $this->assertTrue($this->form->formSubmissions->contains($submission));
        $this->assertTrue($participant->formSubmissions->contains($submission));
    }

    /**
     * Test: Estadísticas de envíos
     */
    public function test_submission_statistics()
    {
        // Crear múltiples envíos
        $participants = Participant::factory()->count(10)->create();
        
        foreach ($participants as $participant) {
            FormSubmission::factory()->create([
                'form_id' => $this->form->id,
                'participant_id' => $participant->id
            ]);
        }

        // Verificar conteo de envíos
        $this->assertEquals(10, $this->form->formSubmissions()->count());
        $this->assertEquals(10, FormSubmission::where('form_id', $this->form->id)->count());
    }

    /**
     * Test: Envío con archivos adjuntos (si está implementado)
     */
    public function test_submission_with_file_attachments()
    {
        // Este test asume que el sistema soporta archivos adjuntos
        // Si no está implementado, se puede comentar o adaptar
        
        $submissionData = [
            'nombre' => 'Juan Con Archivo',
            'email' => 'juan@archivo.com',
            'genero' => 'masculino',
            'cv' => 'archivo_cv.pdf' // Simular archivo adjunto
        ];

        $response = $this->post(route('public.forms.slug.submit', $this->form->slug), $submissionData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Verificar que se almacenó la información del archivo
        $participant = Participant::where('email', 'juan@archivo.com')->first();
        $submission = FormSubmission::where('participant_id', $participant->id)->first();
        
        $this->assertArrayHasKey('cv', $submission->data_json);
    }
}