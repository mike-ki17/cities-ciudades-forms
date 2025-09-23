<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ParticipantService
{
    /**
     * Create a new participant.
     */
    public function createParticipant(array $data): Participant
    {
        return Participant::create([
            'name' => $data['name'] ?? $data['first_name'] ?? 'Usuario',
            'email' => $data['email'] ?? 'anonymous@example.com',
            'phone' => $data['phone'] ?? null,
            'document_type' => $data['document_type'] ?? 'DNI', // Will be saved exactly as provided
            'document_number' => $data['document_number'] ?? '00000000',
            'birth_date' => $data['birth_date'] ?? null,
        ]);
    }

    /**
     * Create or get an existing participant.
     * Priority: document_number (unique) - ONLY search by document_number to prevent duplicates
     */
    public function createOrGetParticipant(array $data): Participant
    {
        return DB::transaction(function () use ($data) {
            // ONLY PRIORITY: Check if participant already exists by document_number (unique field)
            $participant = Participant::byDocument(
                $data['document_type'],
                $data['document_number']
            )->first();

            if ($participant) {
                // Update participant data with new information
                $participant->update([
                    'name' => $data['name'] ?? $participant->name,
                    'email' => $data['email'] ?? $participant->email,
                    'phone' => $data['phone'] ?? $participant->phone,
                    'birth_date' => $data['birth_date'] ?? $participant->birth_date,
                ]);

                \Log::info('Participant found by document, updated existing record', [
                    'participant_id' => $participant->id,
                    'document_type' => $data['document_type'],
                    'document_number' => $data['document_number'],
                    'updated_fields' => ['name', 'email', 'phone', 'birth_date']
                ]);

                return $participant;
            }

            // Create new participant only if document_number doesn't exist
            $participant = Participant::create([
                'name' => $data['name'] ?? 'Usuario',
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'document_type' => $data['document_type'], // Save exactly as provided
                'document_number' => $data['document_number'],
                'birth_date' => $data['birth_date'] ?? null,
            ]);

            \Log::info('New participant created', [
                'participant_id' => $participant->id,
                'name' => $participant->name,
                'email' => $participant->email,
                'document_type' => $participant->document_type,
                'document_number' => $participant->document_number
            ]);

            return $participant;
        });
    }

    /**
     * Sync user with participant.
     */
    public function syncUserWithParticipant(User $user, Participant $participant): User
    {
        $user->update(['participant_id' => $participant->id]);
        return $user->fresh();
    }

    /**
     * Get or create participant for user.
     */
    public function getOrCreateParticipantForUser(User $user, array $participantData = []): Participant
    {
        if ($user->participant_id) {
            return $user->participant;
        }

        // Create participant from user data
        $data = array_merge([
            'name' => $user->name,
            'email' => $user->email,
        ], $participantData);

        $participant = $this->createOrGetParticipant($data);
        $this->syncUserWithParticipant($user, $participant);

        return $participant;
    }

    /**
     * Update participant data.
     */
    public function updateParticipant(Participant $participant, array $data): Participant
    {
        $participant->update($data);
        return $participant->fresh();
    }

    /**
     * Get participant by email (case insensitive).
     */
    public function getParticipantByEmail(string $email): ?Participant
    {
        return Participant::byEmailInsensitive($email)->first();
    }

    /**
     * Get participant by document.
     */
    public function getParticipantByDocument(string $documentType, string $documentNumber): ?Participant
    {
        return Participant::byDocument($documentType, $documentNumber)->first();
    }

    /**
     * Get participants for an event.
     */
    public function getParticipantsForEvent(int $eventId): \Illuminate\Database\Eloquent\Collection
    {
        // Since participants no longer have direct event relationship,
        // we need to get them through form submissions or attendance
        return Participant::whereHas('formSubmissions.form', function($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })->get();
    }

    /**
     * Search participants.
     */
    public function searchParticipants(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return Participant::where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('document_number', 'like', "%{$query}%");
        })->get();
    }

    /**
     * Get participant statistics.
     */
    public function getParticipantStatistics(Participant $participant): array
    {
        $totalSubmissions = $participant->formSubmissions()->count();
        $uniqueForms = $participant->formSubmissions()
            ->distinct('form_id')
            ->count('form_id');

        $latestSubmission = $participant->formSubmissions()
            ->latest('submitted_at')
            ->first();

        return [
            'total_submissions' => $totalSubmissions,
            'unique_forms' => $uniqueForms,
            'latest_submission' => $latestSubmission,
        ];
    }

    /**
     * Check if participant can submit form.
     */
    public function canParticipantSubmitForm(Participant $participant, int $formId): bool
    {
        // For now, allow multiple submissions
        // You can add business logic here to restrict submissions
        return true;
    }

    /**
     * Get participant's form submissions.
     */
    public function getParticipantSubmissions(Participant $participant, array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $participant->formSubmissions()->with(['form', 'form.event']);

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('submitted_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('submitted_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('submitted_at', 'desc')->get();
    }
}
