<?php

namespace Tests\Feature;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\User;
use App\Models\City;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations for testing
        $this->artisan('migrate', ['--env' => 'testing']);
    }

    public function test_user_must_be_authenticated_to_access_form()
    {
        $city = City::create([
            'name' => 'Test City',
            'timezone' => 'America/Bogota'
        ]);

        $form = Form::create([
            'city_id' => $city->id,
            'name' => 'Test Form',
            'slug' => 'test-form',
            'description' => 'Test Description',
            'schema_json' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Name',
                        'type' => 'text',
                        'required' => true
                    ]
                ]
            ],
            'is_active' => true,
            'version' => 1
        ]);

        $response = $this->get("/form/{$form->slug}");
        
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_form()
    {
        $user = User::factory()->create();
        
        $city = City::create([
            'name' => 'Test City',
            'timezone' => 'America/Bogota'
        ]);

        $form = Form::create([
            'city_id' => $city->id,
            'name' => 'Test Form',
            'slug' => 'test-form',
            'description' => 'Test Description',
            'schema_json' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Name',
                        'type' => 'text',
                        'required' => true
                    ]
                ]
            ],
            'is_active' => true,
            'version' => 1
        ]);

        $response = $this->actingAs($user)->get("/form/{$form->slug}");
        
        $response->assertStatus(200);
        $response->assertViewIs('public.forms.show');
    }

    public function test_user_can_submit_form_once()
    {
        $user = User::factory()->create();
        
        $city = City::create([
            'name' => 'Test City',
            'timezone' => 'America/Bogota'
        ]);

        $participant = Participant::create([
            'city_id' => $city->id,
            'name' => 'Test Participant',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'document_type' => 'DNI',
            'document_number' => '12345678'
        ]);

        $user->update(['participant_id' => $participant->id]);

        $form = Form::create([
            'city_id' => $city->id,
            'name' => 'Test Form',
            'slug' => 'test-form',
            'description' => 'Test Description',
            'schema_json' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Name',
                        'type' => 'text',
                        'required' => true
                    ]
                ]
            ],
            'is_active' => true,
            'version' => 1
        ]);

        $formData = [
            'name' => 'John Doe'
        ];

        $response = $this->actingAs($user)->post("/form/{$form->slug}", $formData);
        
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Formulario enviado exitosamente.');

        $this->assertDatabaseHas('form_submission', [
            'form_id' => $form->id,
            'user_id' => $user->id,
            'participant_id' => $participant->id
        ]);
    }

    public function test_user_cannot_submit_form_twice()
    {
        $user = User::factory()->create();
        
        $city = City::create([
            'name' => 'Test City',
            'timezone' => 'America/Bogota'
        ]);

        $participant = Participant::create([
            'city_id' => $city->id,
            'name' => 'Test Participant',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'document_type' => 'DNI',
            'document_number' => '12345678'
        ]);

        $user->update(['participant_id' => $participant->id]);

        $form = Form::create([
            'city_id' => $city->id,
            'name' => 'Test Form',
            'slug' => 'test-form',
            'description' => 'Test Description',
            'schema_json' => [
                'fields' => [
                    [
                        'key' => 'name',
                        'label' => 'Name',
                        'type' => 'text',
                        'required' => true
                    ]
                ]
            ],
            'is_active' => true,
            'version' => 1
        ]);

        // Create existing submission
        FormSubmission::create([
            'form_id' => $form->id,
            'user_id' => $user->id,
            'participant_id' => $participant->id,
            'data_json' => ['name' => 'John Doe'],
            'submitted_at' => now()
        ]);

        $formData = [
            'name' => 'Jane Doe'
        ];

        $response = $this->actingAs($user)->post("/form/{$form->slug}", $formData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ya has completado este formulario. No puedes volver a llenarlo.');

        // Verify only one submission exists
        $this->assertEquals(1, FormSubmission::where('form_id', $form->id)
            ->where('user_id', $user->id)
            ->count());
    }

    public function test_user_model_has_submitted_form_method()
    {
        $user = User::factory()->create();
        
        $city = City::create([
            'name' => 'Test City',
            'timezone' => 'America/Bogota'
        ]);

        $form = Form::create([
            'city_id' => $city->id,
            'name' => 'Test Form',
            'slug' => 'test-form',
            'description' => 'Test Description',
            'schema_json' => ['fields' => []],
            'is_active' => true,
            'version' => 1
        ]);

        $this->assertFalse($user->hasSubmittedForm($form->id));

        FormSubmission::create([
            'form_id' => $form->id,
            'user_id' => $user->id,
            'participant_id' => null,
            'data_json' => [],
            'submitted_at' => now()
        ]);

        $this->assertTrue($user->hasSubmittedForm($form->id));
    }
}