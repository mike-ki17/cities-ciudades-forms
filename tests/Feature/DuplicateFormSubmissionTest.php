<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Form;
use App\Models\Participant;
use App\Models\FormSubmission;
use App\Services\FormService;
use App\Services\ParticipantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DuplicateFormSubmissionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Event $event;
    private Form $form;
    private Participant $participant;
    private FormService $formService;
    private ParticipantService $participantService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->formService = app(FormService::class);
        $this->participantService = app(ParticipantService::class);
        
        // Create test event
        $this->event = Event::create([
            'name' => 'Test Event',
            'city' => 'Test City',
            'year' => 2024,
            'description' => 'Test event description',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(2),
            'is_active' => true,
        ]);
        
        // Create test form
        $this->form = Form::create([
            'name' => 'Test Form',
            'description' => 'Test form description',
            'event_id' => $this->event->id,
            'slug' => 'test-form',
            'is_active' => true,
            'version' => 1,
        ]);
        
        // Create test participant
        $this->participant = Participant::create([
            'name' => 'Test Participant',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'document_type' => 'DNI',
            'document_number' => '12345678',
            'birth_date' => now()->subYears(25),
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_form_submissions()
    {
        // First submission should succeed
        $submission1 = $this->formService->submitForm($this->form, $this->participant, [
            'test_field' => 'test value'
        ]);
        
        $this->assertInstanceOf(FormSubmission::class, $submission1);
        $this->assertEquals($this->form->id, $submission1->form_id);
        $this->assertEquals($this->participant->id, $submission1->participant_id);
        
        // Check that participant has submitted the form
        $this->assertTrue($this->formService->hasParticipantSubmitted($this->form, $this->participant));
        
        // Second submission should be prevented by the controller
        $response = $this->post(route('public.forms.slug.submit', [
            'slug' => $this->form->slug
        ]), [
            'name' => $this->participant->name,
            'email' => $this->participant->email,
            'phone' => $this->participant->phone,
            'document_type' => $this->participant->document_type,
            'document_number' => $this->participant->document_number,
            'birth_date' => $this->participant->birth_date?->format('Y-m-d'),
            'test_field' => 'another test value'
        ]);
        
        // Should redirect back with error message
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ya has llenado este formulario anteriormente. No puedes enviarlo nuevamente.');
        
        // Verify only one submission exists
        $this->assertEquals(1, FormSubmission::where('form_id', $this->form->id)
            ->where('participant_id', $this->participant->id)
            ->count());
    }

    /** @test */
    public function it_shows_form_as_already_submitted_when_participant_has_submitted()
    {
        // Create a submission
        $this->formService->submitForm($this->form, $this->participant, [
            'test_field' => 'test value'
        ]);
        
        // Store participant ID in session to simulate previous submission
        $this->session(['participant_id' => $this->participant->id]);
        
        // Visit the form page
        $response = $this->get(route('public.forms.slug.show', ['slug' => $this->form->slug]));
        
        $response->assertStatus(200);
        $response->assertViewIs('public.forms.show');
        
        // Check that the view has the correct data
        $response->assertViewHas('hasSubmitted', true);
        $response->assertViewHas('participant', $this->participant);
        $response->assertViewHas('latestSubmission');
        
        // Check that the form shows the "already submitted" message
        $response->assertSee('Formulario ya enviado');
        $response->assertSee('No puedes enviarlo nuevamente');
    }

    /** @test */
    public function it_allows_different_participants_to_submit_same_form()
    {
        // Create another participant
        $participant2 = Participant::create([
            'name' => 'Test Participant 2',
            'email' => 'test2@example.com',
            'phone' => '987654321',
            'document_type' => 'DNI',
            'document_number' => '87654321',
            'birth_date' => now()->subYears(30),
        ]);
        
        // First participant submits
        $submission1 = $this->formService->submitForm($this->form, $this->participant, [
            'test_field' => 'participant 1 value'
        ]);
        
        // Second participant should be able to submit
        $submission2 = $this->formService->submitForm($this->form, $participant2, [
            'test_field' => 'participant 2 value'
        ]);
        
        $this->assertInstanceOf(FormSubmission::class, $submission1);
        $this->assertInstanceOf(FormSubmission::class, $submission2);
        
        // Both submissions should exist
        $this->assertEquals(2, FormSubmission::where('form_id', $this->form->id)->count());
        $this->assertEquals(1, FormSubmission::where('form_id', $this->form->id)
            ->where('participant_id', $this->participant->id)
            ->count());
        $this->assertEquals(1, FormSubmission::where('form_id', $this->form->id)
            ->where('participant_id', $participant2->id)
            ->count());
    }

    /** @test */
    public function it_allows_same_participant_to_submit_different_forms()
    {
        // Create another form
        $form2 = Form::create([
            'name' => 'Test Form 2',
            'description' => 'Test form 2 description',
            'event_id' => $this->event->id,
            'slug' => 'test-form-2',
            'is_active' => true,
            'version' => 1,
        ]);
        
        // Participant submits first form
        $submission1 = $this->formService->submitForm($this->form, $this->participant, [
            'test_field' => 'form 1 value'
        ]);
        
        // Participant should be able to submit second form
        $submission2 = $this->formService->submitForm($form2, $this->participant, [
            'test_field' => 'form 2 value'
        ]);
        
        $this->assertInstanceOf(FormSubmission::class, $submission1);
        $this->assertInstanceOf(FormSubmission::class, $submission2);
        
        // Both submissions should exist
        $this->assertEquals(1, FormSubmission::where('form_id', $this->form->id)
            ->where('participant_id', $this->participant->id)
            ->count());
        $this->assertEquals(1, FormSubmission::where('form_id', $form2->id)
            ->where('participant_id', $this->participant->id)
            ->count());
    }

    /** @test */
    public function it_uses_participant_model_hasSubmittedForm_method()
    {
        // Test the model method directly
        $this->assertFalse($this->participant->hasSubmittedForm($this->form->id));
        
        // Create a submission
        $this->formService->submitForm($this->form, $this->participant, [
            'test_field' => 'test value'
        ]);
        
        // Now it should return true
        $this->assertTrue($this->participant->hasSubmittedForm($this->form->id));
    }

    /** @test */
    public function it_gets_latest_submission_for_participant_and_form()
    {
        // Create a submission
        $submission = $this->formService->submitForm($this->form, $this->participant, [
            'test_field' => 'test value'
        ]);
        
        // Get latest submission using service method
        $latestSubmission = $this->formService->getLatestParticipantSubmission($this->form, $this->participant);
        
        $this->assertInstanceOf(FormSubmission::class, $latestSubmission);
        $this->assertEquals($submission->id, $latestSubmission->id);
        
        // Test model method as well
        $modelLatestSubmission = $this->participant->getLatestSubmissionForForm($this->form->id);
        
        $this->assertInstanceOf(FormSubmission::class, $modelLatestSubmission);
        $this->assertEquals($submission->id, $modelLatestSubmission->id);
    }
}
