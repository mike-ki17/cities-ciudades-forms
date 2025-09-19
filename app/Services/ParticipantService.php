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
            'document_type' => $data['document_type'] ?? 'DNI',
            'document_number' => $data['document_number'] ?? '00000000',
            'city_id' => $data['city_id'] ?? null,
        ]);
    }

    /**
     * Create or get an existing participant.
     */
    public function createOrGetParticipant(array $data): Participant
    {
        return DB::transaction(function () use ($data) {
            // Check if participant already exists by email (case insensitive)
            $participant = Participant::byEmailInsensitive($data['email'])->first();

            if ($participant) {
                // Update participant data if needed
                $participant->update([
                    'name' => $data['name'] ?? $data['first_name'] ?? $participant->name,
                    'phone' => $data['phone'] ?? $participant->phone,
                    'document_type' => $data['document_type'],
                    'document_number' => $data['document_number'],
                    'city_id' => $data['city_id'] ?? $participant->city_id,
                ]);

                return $participant;
            }

            // Check if participant exists by document
            $participant = Participant::byDocument(
                $data['document_type'],
                $data['document_number']
            )->first();

            if ($participant) {
                // Update participant data if needed
                $participant->update([
                    'name' => $data['name'] ?? $data['first_name'] ?? 'Usuario',
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? $participant->phone,
                    'city_id' => $data['city_id'] ?? $participant->city_id,
                ]);

                return $participant;
            }

            // Create new participant
            return Participant::create([
                'name' => $data['name'] ?? $data['first_name'] ?? 'Usuario',
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'document_type' => $data['document_type'],
                'document_number' => $data['document_number'],
                'city_id' => $data['city_id'] ?? null,
            ]);
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
     * Get participants for a city.
     */
    public function getParticipantsForCity(int $cityId): \Illuminate\Database\Eloquent\Collection
    {
        return Participant::forCity($cityId)->get();
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
        $query = $participant->formSubmissions()->with(['form', 'form.city']);

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
