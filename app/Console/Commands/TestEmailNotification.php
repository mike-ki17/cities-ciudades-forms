<?php

namespace App\Console\Commands;

use App\Mail\ParticipationNotificationMail;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-notification {email} {--form-id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notification for form participation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $formId = $this->option('form-id');

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return 1;
        }

        // Get form
        $form = Form::find($formId);
        if (!$form) {
            $this->error("Form with ID {$formId} not found.");
            return 1;
        }

        // Create test participant
        $participant = new Participant([
            'name' => 'Test User',
            'email' => $email,
            'phone' => '1234567890',
            'document_type' => 'DNI',
            'document_number' => '12345678',
            'birth_date' => '1990-01-01',
            'event_id' => $form->event_id ?? 1,
        ]);

        // Create test submission
        $submission = new FormSubmission([
            'form_id' => $form->id,
            'participant_id' => 1, // Dummy ID
            'data_json' => [
                'test_field' => 'Test Value',
                'another_field' => 'Another Test Value'
            ],
            'submitted_at' => now(),
        ]);

        $this->info("Sending test email to: {$email}");
        $this->info("Form: {$form->name}");

        try {
            Mail::to($email)->send(new ParticipationNotificationMail($form, $participant, $submission));
            $this->info('✅ Email sent successfully!');
            $this->info('Check your inbox and spam folder.');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
