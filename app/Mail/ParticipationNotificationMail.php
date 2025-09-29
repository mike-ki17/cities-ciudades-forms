<?php

namespace App\Mail;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Form $form;
    public Participant $participant;
    public FormSubmission $submission;

    /**
     * Create a new message instance.
     */
    public function __construct(Form $form, Participant $participant, FormSubmission $submission)
    {
        $this->form = $form;
        $this->participant = $participant;
        $this->submission = $submission;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Participación - ' . $this->form->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.participation-notification', // Usar template mejorado
            with: [
                'form' => $this->form,
                'participant' => $this->participant,
                'submission' => $this->submission,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
