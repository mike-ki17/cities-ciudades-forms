<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Form;
use App\Models\FormCategory;
use App\Models\FormOption;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Modelo Event - Creación y atributos
     */
    public function test_event_model_creation()
    {
        $event = Event::create([
            'name' => 'Festival de Cine 2024',
            'city' => 'Bogotá',
            'year' => 2024
        ]);

        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals('Festival de Cine 2024', $event->name);
        $this->assertEquals('Bogotá', $event->city);
        $this->assertEquals(2024, $event->year);
        $this->assertNotNull($event->created_at);
        $this->assertNotNull($event->updated_at);
    }

    /**
     * Test: Modelo Event - Relaciones
     */
    public function test_event_model_relationships()
    {
        $event = Event::factory()->create();
        $form = Form::factory()->create(['event_id' => $event->id]);

        // Verificar relación con formularios
        $this->assertTrue($event->forms->contains($form));
        $this->assertEquals(1, $event->forms()->count());
    }

    /**
     * Test: Modelo Event - Scopes
     */
    public function test_event_model_scopes()
    {
        Event::factory()->create(['name' => 'Festival de Cine', 'city' => 'Bogotá']);
        Event::factory()->create(['name' => 'Festival de Música', 'city' => 'Medellín']);

        // Test scope byNameInsensitive
        $cineEvents = Event::byNameInsensitive('festival de cine')->get();
        $this->assertCount(1, $cineEvents);
        $this->assertEquals('Festival de Cine', $cineEvents->first()->name);

        // Test scope byCityInsensitive
        $bogotaEvents = Event::byCityInsensitive('bogotá')->get();
        $this->assertCount(1, $bogotaEvents);
        $this->assertEquals('Bogotá', $bogotaEvents->first()->city);
    }

    /**
     * Test: Modelo Form - Creación y atributos
     */
    public function test_form_model_creation()
    {
        $event = Event::factory()->create();
        
        $form = Form::create([
            'event_id' => $event->id,
            'name' => 'Formulario de Inscripción',
            'slug' => 'formulario-inscripcion',
            'description' => 'Formulario para inscribirse',
            'is_active' => true,
            'version' => 1,
            'schema_json' => ['fields' => []],
            'style_json' => ['theme' => 'default']
        ]);

        $this->assertInstanceOf(Form::class, $form);
        $this->assertEquals('Formulario de Inscripción', $form->name);
        $this->assertEquals('formulario-inscripcion', $form->slug);
        $this->assertTrue($form->is_active);
        $this->assertEquals(1, $form->version);
        $this->assertIsArray($form->schema_json);
        $this->assertIsArray($form->style_json);
    }

    /**
     * Test: Modelo Form - Relaciones
     */
    public function test_form_model_relationships()
    {
        $event = Event::factory()->create();
        $form = Form::factory()->create(['event_id' => $event->id]);
        $participant = Participant::factory()->create();
        $submission = FormSubmission::factory()->create([
            'form_id' => $form->id,
            'participant_id' => $participant->id
        ]);

        // Verificar relación con evento
        $this->assertEquals($event->id, $form->event->id);

        // Verificar relación con envíos
        $this->assertTrue($form->formSubmissions->contains($submission));
        $this->assertEquals(1, $form->formSubmissions()->count());
    }

    /**
     * Test: Modelo Form - Soft Deletes
     */
    public function test_form_model_soft_deletes()
    {
        $form = Form::factory()->create();
        $formId = $form->id;

        // Eliminar suavemente
        $form->delete();

        // Verificar que está marcado como eliminado
        $this->assertSoftDeleted('forms', ['id' => $formId]);

        // Verificar que no aparece en consultas normales
        $this->assertNull(Form::find($formId));

        // Verificar que aparece en consultas con trashed
        $this->assertNotNull(Form::withTrashed()->find($formId));
    }

    /**
     * Test: Modelo FormCategory - Creación y atributos
     */
    public function test_form_category_model_creation()
    {
        $category = FormCategory::create([
            'code' => 'genero',
            'name' => 'Género',
            'description' => 'Campo para seleccionar género',
            'is_active' => true
        ]);

        $this->assertInstanceOf(FormCategory::class, $category);
        $this->assertEquals('genero', $category->code);
        $this->assertEquals('Género', $category->name);
        $this->assertTrue($category->is_active);
    }

    /**
     * Test: Modelo FormCategory - Relaciones
     */
    public function test_form_category_model_relationships()
    {
        $category = FormCategory::factory()->create();
        $option1 = FormOption::factory()->create(['category_id' => $category->id]);
        $option2 = FormOption::factory()->create(['category_id' => $category->id]);

        // Verificar relación con opciones
        $this->assertTrue($category->formOptions->contains($option1));
        $this->assertTrue($category->formOptions->contains($option2));
        $this->assertEquals(2, $category->formOptions()->count());
    }

    /**
     * Test: Modelo FormOption - Creación y atributos
     */
    public function test_form_option_model_creation()
    {
        $category = FormCategory::factory()->create();
        
        $option = FormOption::create([
            'category_id' => $category->id,
            'value' => 'masculino',
            'label' => 'Masculino',
            'description' => 'Opción para género masculino',
            'order' => 1,
            'is_active' => true
        ]);

        $this->assertInstanceOf(FormOption::class, $option);
        $this->assertEquals('masculino', $option->value);
        $this->assertEquals('Masculino', $option->label);
        $this->assertEquals(1, $option->order);
        $this->assertTrue($option->is_active);
    }

    /**
     * Test: Modelo FormOption - Relaciones
     */
    public function test_form_option_model_relationships()
    {
        $category = FormCategory::factory()->create();
        $option = FormOption::factory()->create(['category_id' => $category->id]);

        // Verificar relación con categoría
        $this->assertEquals($category->id, $option->category->id);
    }

    /**
     * Test: Modelo FormOption - Relaciones padre-hijo
     */
    public function test_form_option_parent_child_relationships()
    {
        $category = FormCategory::factory()->create();
        $parentOption = FormOption::factory()->create(['category_id' => $category->id]);
        $childOption = FormOption::factory()->create(['category_id' => $category->id]);

        // Crear relación padre-hijo
        $parentOption->childOptions()->attach($childOption->id);

        // Verificar relaciones
        $this->assertTrue($parentOption->childOptions->contains($childOption));
        $this->assertTrue($childOption->parentOptions->contains($parentOption));
    }

    /**
     * Test: Modelo Participant - Creación y atributos
     */
    public function test_participant_model_creation()
    {
        $participant = Participant::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'phone' => '+57 300 123 4567',
            'document_type' => 'CC',
            'document_number' => '12345678',
            'birth_date' => '1990-01-01'
        ]);

        $this->assertInstanceOf(Participant::class, $participant);
        $this->assertEquals('Juan Pérez', $participant->name);
        $this->assertEquals('juan@example.com', $participant->email);
        $this->assertEquals('CC', $participant->document_type);
        $this->assertEquals('12345678', $participant->document_number);
        $this->assertInstanceOf(\Carbon\Carbon::class, $participant->birth_date);
    }

    /**
     * Test: Modelo Participant - Relaciones
     */
    public function test_participant_model_relationships()
    {
        $participant = Participant::factory()->create();
        $form = Form::factory()->create();
        $submission = FormSubmission::factory()->create([
            'participant_id' => $participant->id,
            'form_id' => $form->id
        ]);

        // Verificar relación con envíos
        $this->assertTrue($participant->formSubmissions->contains($submission));
        $this->assertEquals(1, $participant->formSubmissions()->count());
    }

    /**
     * Test: Modelo Participant - Accessors
     */
    public function test_participant_model_accessors()
    {
        $participant = Participant::factory()->create(['name' => 'Juan Carlos Pérez']);

        // Test getFullNameAttribute
        $this->assertEquals('Juan Carlos Pérez', $participant->full_name);

        // Test getFirstNameAttribute
        $this->assertEquals('Juan', $participant->first_name);
    }

    /**
     * Test: Modelo Participant - Soft Deletes
     */
    public function test_participant_model_soft_deletes()
    {
        $participant = Participant::factory()->create();
        $participantId = $participant->id;

        // Eliminar suavemente
        $participant->delete();

        // Verificar que está marcado como eliminado
        $this->assertSoftDeleted('participants', ['id' => $participantId]);

        // Verificar que no aparece en consultas normales
        $this->assertNull(Participant::find($participantId));

        // Verificar que aparece en consultas con trashed
        $this->assertNotNull(Participant::withTrashed()->find($participantId));
    }

    /**
     * Test: Modelo FormSubmission - Creación y atributos
     */
    public function test_form_submission_model_creation()
    {
        $form = Form::factory()->create();
        $participant = Participant::factory()->create();
        
        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'participant_id' => $participant->id,
            'data_json' => [
                'nombre' => 'Juan Pérez',
                'email' => 'juan@example.com'
            ],
            'submitted_at' => now()
        ]);

        $this->assertInstanceOf(FormSubmission::class, $submission);
        $this->assertEquals($form->id, $submission->form_id);
        $this->assertEquals($participant->id, $submission->participant_id);
        $this->assertIsArray($submission->data_json);
        $this->assertEquals('Juan Pérez', $submission->data_json['nombre']);
        $this->assertInstanceOf(\Carbon\Carbon::class, $submission->submitted_at);
    }

    /**
     * Test: Modelo FormSubmission - Relaciones
     */
    public function test_form_submission_model_relationships()
    {
        $form = Form::factory()->create();
        $participant = Participant::factory()->create();
        $submission = FormSubmission::factory()->create([
            'form_id' => $form->id,
            'participant_id' => $participant->id
        ]);

        // Verificar relación con formulario
        $this->assertEquals($form->id, $submission->form->id);

        // Verificar relación con participante
        $this->assertEquals($participant->id, $submission->participant->id);
    }

    /**
     * Test: Modelo User - Creación y atributos
     */
    public function test_user_model_creation()
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Admin User', $user->name);
        $this->assertEquals('admin@example.com', $user->email);
        $this->assertTrue($user->is_admin);
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test: Modelo User - Relaciones
     */
    public function test_user_model_relationships()
    {
        $participant = Participant::factory()->create();
        $user = User::factory()->create(['participant_id' => $participant->id]);

        // Verificar relación con participante
        $this->assertEquals($participant->id, $user->participant->id);
    }

    /**
     * Test: Modelo User - Métodos de verificación
     */
    public function test_user_model_verification_methods()
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        $regularUser = User::factory()->create(['is_admin' => false]);

        // Test isAdmin method
        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    /**
     * Test: Modelo User - Método hasSubmittedForm
     */
    public function test_user_has_submitted_form_method()
    {
        $participant = Participant::factory()->create();
        $user = User::factory()->create(['participant_id' => $participant->id]);
        $form = Form::factory()->create();
        
        // Usuario sin envíos
        $this->assertFalse($user->hasSubmittedForm($form->id));

        // Crear envío
        FormSubmission::factory()->create([
            'form_id' => $form->id,
            'participant_id' => $participant->id
        ]);

        // Usuario con envío
        $this->assertTrue($user->hasSubmittedForm($form->id));
    }

    /**
     * Test: Validación de unicidad en modelos
     */
    public function test_model_uniqueness_validation()
    {
        // Test Form slug uniqueness
        Form::factory()->create(['slug' => 'unique-slug']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        Form::factory()->create(['slug' => 'unique-slug']);

        // Test FormCategory code uniqueness
        FormCategory::factory()->create(['code' => 'unique-code']);
        
        $this->expectException(\Illuminate\Database\QueryException::class);
        FormCategory::factory()->create(['code' => 'unique-code']);
    }

    /**
     * Test: Casts de atributos en modelos
     */
    public function test_model_attribute_casts()
    {
        $form = Form::factory()->create([
            'is_active' => true,
            'version' => 5,
            'schema_json' => ['test' => 'data'],
            'style_json' => ['theme' => 'dark']
        ]);

        // Verificar casts
        $this->assertIsBool($form->is_active);
        $this->assertIsInt($form->version);
        $this->assertIsArray($form->schema_json);
        $this->assertIsArray($form->style_json);
        $this->assertInstanceOf(\Carbon\Carbon::class, $form->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $form->updated_at);
    }

    /**
     * Test: Fillable attributes en modelos
     */
    public function test_model_fillable_attributes()
    {
        $eventData = [
            'name' => 'Test Event',
            'city' => 'Test City',
            'year' => 2024
        ];

        $event = Event::create($eventData);
        
        foreach ($eventData as $key => $value) {
            $this->assertEquals($value, $event->$key);
        }
    }
}
