<?php

namespace App\Repositories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;

class FormRepository
{
    /**
     * Get the active form for a specific event.
     */
    public function getActiveFormForEvent(int $eventId): ?Form
    {
        return Form::active()
            ->forEvent($eventId)
            ->latestVersion()
            ->first();
    }


    /**
     * Get all forms.
     */
    public function getAll(): Collection
    {
        return Form::with('event')
            ->orderBy('event_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get active forms.
     */
    public function getActive(): Collection
    {
        return Form::active()
            ->with('event')
            ->orderBy('event_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Find a form by ID.
     */
    public function findById(int $id): ?Form
    {
        return Form::with('event')->find($id);
    }

    /**
     * Create a new form.
     */
    public function create(array $data): Form
    {
        return Form::create($data);
    }

    /**
     * Update a form.
     */
    public function update(Form $form, array $data): Form
    {
        $form->update($data);
        return $form->fresh();
    }

    /**
     * Delete a form (soft delete).
     */
    public function delete(Form $form): bool
    {
        return $form->delete();
    }

    /**
     * Get forms for a specific event.
     */
    public function getFormsForEvent(int $eventId): Collection
    {
        return Form::forEvent($eventId)
            ->with('event')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get active forms for a specific event.
     */
    public function getActiveFormsForEvent(int $eventId): Collection
    {
        return Form::active()
            ->forEvent($eventId)
            ->with('event')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get active forms for a specific event (alias for backward compatibility).
     */
    public function getActiveFormsForCity(int $eventId): Collection
    {
        return $this->getActiveFormsForEvent($eventId);
    }


    /**
     * Get forms with submissions count.
     */
    public function getFormsWithSubmissionsCount(): Collection
    {
        return Form::with(['event', 'formSubmissions'])
            ->withCount('formSubmissions')
            ->orderBy('event_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Search forms by title or description.
     */
    public function searchByTitleOrDescription(string $query): Collection
    {
        return Form::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with('event')
            ->orderBy('event_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get forms by version range.
     */
    public function getFormsByVersionRange(int $minVersion, int $maxVersion): Collection
    {
        return Form::whereBetween('version', [$minVersion, $maxVersion])
            ->with('event')
            ->orderBy('event_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get the latest version for an event.
     */
    public function getLatestVersionForEvent(int $eventId): int
    {
        return Form::forEvent($eventId)->max('version') ?? 0;
    }


    /**
     * Get form statistics.
     */
    public function getFormStatistics(Form $form): array
    {
        $totalSubmissions = $form->formSubmissions()->count();
        $uniqueParticipants = $form->formSubmissions()
            ->distinct('participant_id')
            ->count('participant_id');

        $submissionsByDate = $form->formSubmissions()
            ->selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total_submissions' => $totalSubmissions,
            'unique_participants' => $uniqueParticipants,
            'submissions_by_date' => $submissionsByDate,
        ];
    }
}
