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
     * $data should contain only dynamic fields (already separated in controller)
     */
    public function submitForm(Form $form, Participant $participant, array $data, $user = null): FormSubmission
    {
        return DB::transaction(function () use ($form, $participant, $data, $user) {
            // Create the submission with dynamic fields in data_json
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'participant_id' => $participant->id,
                'user_id' => $user ? $user->id : null,
                'data_json' => $data, // Only dynamic fields (already separated)
                'submitted_at' => now(),
            ]);

            \Log::info('Form submission created', [
                'submission_id' => $submission->id,
                'form_id' => $form->id,
                'participant_id' => $participant->id,
                'dynamic_fields_count' => count($data),
                'dynamic_fields' => array_keys($data)
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
     * Check if a user has already submitted a form.
     */
    public function hasUserSubmitted(Form $form, $user): bool
    {
        return $form->formSubmissions()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get the latest submission for a user and form.
     */
    public function getLatestUserSubmission(Form $form, $user): ?FormSubmission
    {
        return $form->formSubmissions()
            ->where('user_id', $user->id)
            ->latest('submitted_at')
            ->first();
    }
}
