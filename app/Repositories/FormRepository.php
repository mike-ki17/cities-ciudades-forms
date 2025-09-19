<?php

namespace App\Repositories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;

class FormRepository
{
    /**
     * Get the active form for a specific city.
     */
    public function getActiveFormForCity(int $cityId): ?Form
    {
        return Form::active()
            ->forCity($cityId)
            ->latestVersion()
            ->first();
    }


    /**
     * Get all forms.
     */
    public function getAll(): Collection
    {
        return Form::with('city')
            ->orderBy('city_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get active forms.
     */
    public function getActive(): Collection
    {
        return Form::active()
            ->with('city')
            ->orderBy('city_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Find a form by ID.
     */
    public function findById(int $id): ?Form
    {
        return Form::with('city')->find($id);
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
     * Get forms for a specific city.
     */
    public function getFormsForCity(int $cityId): Collection
    {
        return Form::forCity($cityId)
            ->with('city')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get active forms for a specific city.
     */
    public function getActiveFormsForCity(int $cityId): Collection
    {
        return Form::active()
            ->forCity($cityId)
            ->with('city')
            ->orderBy('version', 'desc')
            ->get();
    }


    /**
     * Get forms with submissions count.
     */
    public function getFormsWithSubmissionsCount(): Collection
    {
        return Form::with(['city', 'formSubmissions'])
            ->withCount('formSubmissions')
            ->orderBy('city_id')
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
            ->with('city')
            ->orderBy('city_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get forms by version range.
     */
    public function getFormsByVersionRange(int $minVersion, int $maxVersion): Collection
    {
        return Form::whereBetween('version', [$minVersion, $maxVersion])
            ->with('city')
            ->orderBy('city_id')
            ->orderBy('version', 'desc')
            ->get();
    }

    /**
     * Get the latest version for a city.
     */
    public function getLatestVersionForCity(int $cityId): int
    {
        return Form::forCity($cityId)->max('version') ?? 0;
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
