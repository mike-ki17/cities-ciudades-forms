<?php

namespace App\Repositories;

use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SubmissionRepository
{
    /**
     * Get all submissions with pagination.
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::with(['form', 'participant', 'form.city'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions with filters.
     */
    public function getWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
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

        if (isset($filters['participant_id'])) {
            $query->where('participant_id', $filters['participant_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('submitted_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('submitted_at', '<=', $filters['date_to']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('participant', function ($participantQuery) use ($search) {
                    $participantQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('form', function ($formQuery) use ($search) {
                    $formQuery->where('title', 'like', "%{$search}%");
                });
            });
        }

        return $query->orderBy('submitted_at', 'desc')->paginate($perPage);
    }

    /**
     * Get submissions for a specific form.
     */
    public function getForForm(int $formId, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::where('form_id', $formId)
            ->with(['participant', 'form.city'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions for a specific city.
     */
    public function getForCity(int $cityId, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::whereHas('form', function ($query) use ($cityId) {
                $query->where('city_id', $cityId);
            })
            ->with(['form', 'participant'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions for a specific participant.
     */
    public function getForParticipant(int $participantId, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::where('participant_id', $participantId)
            ->with(['form', 'form.city'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions within a date range.
     */
    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::whereBetween('submitted_at', [$startDate, $endDate])
            ->with(['form', 'participant', 'form.city'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find a submission by ID.
     */
    public function findById(int $id): ?FormSubmission
    {
        return FormSubmission::with(['form', 'participant', 'form.city'])->find($id);
    }

    /**
     * Create a new submission.
     */
    public function create(array $data): FormSubmission
    {
        return FormSubmission::create($data);
    }

    /**
     * Update a submission.
     */
    public function update(FormSubmission $submission, array $data): FormSubmission
    {
        $submission->update($data);
        return $submission->fresh();
    }

    /**
     * Delete a submission (soft delete).
     */
    public function delete(FormSubmission $submission): bool
    {
        return $submission->delete();
    }

    /**
     * Get submission statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = FormSubmission::query();

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

        $totalSubmissions = $query->count();
        $uniqueParticipants = $query->distinct('participant_id')->count('participant_id');

        $submissionsByDate = $query->selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $submissionsByForm = $query->with('form')
            ->get()
            ->groupBy('form.title')
            ->map(function ($submissions) {
                return $submissions->count();
            });

        return [
            'total_submissions' => $totalSubmissions,
            'unique_participants' => $uniqueParticipants,
            'submissions_by_date' => $submissionsByDate,
            'submissions_by_form' => $submissionsByForm,
        ];
    }

    /**
     * Get recent submissions.
     */
    public function getRecent(int $limit = 10): Collection
    {
        return FormSubmission::with(['form', 'participant', 'form.city'])
            ->orderBy('submitted_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search submissions.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::where(function ($q) use ($query) {
                $q->whereHas('participant', function ($participantQuery) use ($query) {
                    $participantQuery->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->orWhereHas('form', function ($formQuery) use ($query) {
                    $formQuery->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->with(['form', 'participant', 'form.city'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }
}
