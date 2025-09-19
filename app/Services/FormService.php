<?php

namespace App\Services;

use App\Models\City;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Repositories\CityRepository;
use App\Repositories\FormRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormService
{
    public function __construct(
        private FormRepository $formRepository,
        private CityRepository $cityRepository
    ) {}

    /**
     * Get all active forms for a city.
     */
    public function getActiveFormsForCity(string $cityName): \Illuminate\Database\Eloquent\Collection
    {
        $city = $this->cityRepository->findByNameInsensitive($cityName);
        
        if (!$city) {
            return collect();
        }

        return $this->formRepository->getActiveFormsForCity($city->id);
    }

    /**
     * Get the first active form for a city (for backward compatibility).
     */
    public function getActiveFormForCity(string $cityName): ?Form
    {
        $forms = $this->getActiveFormsForCity($cityName);
        return $forms->first();
    }

    /**
     * Get a form by its slug.
     */
    public function getFormBySlug(string $slug): ?Form
    {
        return Form::bySlug($slug)->active()->first();
    }

    /**
     * Create a new form.
     */
    public function createForm(array $data): Form
    {
        return DB::transaction(function () use ($data) {
            // Increment version for the city
            $latestVersion = Form::where('city_id', $data['city_id'])
                ->max('version') ?? 0;
            $data['version'] = $latestVersion + 1;

            return Form::create($data);
        });
    }

    /**
     * Update an existing form.
     */
    public function updateForm(Form $form, array $data): Form
    {
        return DB::transaction(function () use ($form, $data) {
            // If schema_json is being updated, create a new version
            if (isset($data['schema_json']) && $data['schema_json'] !== $form->schema_json) {
                $data['version'] = $form->version + 1;
            }

            $form->update($data);
            return $form->fresh();
        });
    }

    /**
     * Activate a form (allow multiple active forms per city).
     */
    public function activateForm(Form $form): Form
    {
        $form->update(['is_active' => true]);
        return $form->fresh();
    }

    /**
     * Deactivate a form.
     */
    public function deactivateForm(Form $form): Form
    {
        $form->update(['is_active' => false]);
        return $form->fresh();
    }

    /**
     * Generate validation rules from form schema.
     */
    public function generateValidationRules(Form $form, array $formData = []): array
    {
        return $form->getValidationRules($formData);
    }

    /**
     * Validate form submission data.
     */
    public function validateSubmissionData(Form $form, array $data): array
    {
        $rules = $this->generateValidationRules($form, $data);
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Submit a form.
     */
    public function submitForm(Form $form, Participant $participant, array $data): FormSubmission
    {
        return DB::transaction(function () use ($form, $participant, $data) {
            // Validate the data
            $validatedData = $this->validateSubmissionData($form, $data);

            // Create the submission
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'participant_id' => $participant->id,
                'data_json' => $validatedData,
                'submitted_at' => now(),
            ]);

            return $submission;
        });
    }

    /**
     * Get form submissions with filters.
     */
    public function getSubmissions(array $filters = []): Collection
    {
        $query = FormSubmission::with(['form', 'participant', 'form.city']);

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['city_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('city_id', $filters['city_id']);
            });
        }

        if (isset($filters['date_from'])) {
            $query->where('submitted_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('submitted_at', '<=', $filters['date_to']);
        }

        if (isset($filters['participant_id'])) {
            $query->where('participant_id', $filters['participant_id']);
        }

        return $query->orderBy('submitted_at', 'desc')->get();
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

    /**
     * Check if a participant has already submitted a form.
     */
    public function hasParticipantSubmitted(Form $form, Participant $participant): bool
    {
        return $form->formSubmissions()
            ->where('participant_id', $participant->id)
            ->exists();
    }

    /**
     * Get the latest submission for a participant and form.
     */
    public function getLatestSubmission(Form $form, Participant $participant): ?FormSubmission
    {
        return $form->formSubmissions()
            ->where('participant_id', $participant->id)
            ->latest('submitted_at')
            ->first();
    }
}
