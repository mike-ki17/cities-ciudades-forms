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
        return FormSubmission::with(['form', 'participant', 'form.event'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions with filters.
     */
    public function getWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = FormSubmission::with(['form', 'participant', 'form.event']);

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['event_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('event_id', $filters['event_id']);
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
            ->with(['participant', 'form.event'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions for a specific city.
     */
    public function getForCity(int $cityId, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::whereHas('form', function ($query) use ($cityId) {
                $query->where('event_id', $cityId);
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
            ->with(['form', 'form.event'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get submissions within a date range.
     */
    public function getByDateRange(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return FormSubmission::whereBetween('submitted_at', [$startDate, $endDate])
            ->with(['form', 'participant', 'form.event'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find a submission by ID.
     */
    public function findById(int $id): ?FormSubmission
    {
        return FormSubmission::with(['form', 'participant', 'form.event'])->find($id);
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

        if (isset($filters['event_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('event_id', $filters['event_id']);
            });
        }

        if (isset($filters['date_from'])) {
            $query->where('submitted_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('submitted_at', '<=', $filters['date_to']);
        }

        // Estadísticas básicas
        $totalSubmissions = $query->count();
        $uniqueParticipants = $query->distinct('participant_id')->count('participant_id');
        
        // Estadísticas por fecha (últimos 30 días)
        $dateFrom = $filters['date_from'] ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $filters['date_to'] ?? now()->format('Y-m-d');
        
        $submissionsByDate = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->whereBetween('submitted_at', [$dateFrom, $dateTo])
            ->when(isset($filters['form_id']), function ($q) use ($filters) {
                $q->where('form_id', $filters['form_id']);
            })
            ->when(isset($filters['event_id']), function ($q) use ($filters) {
                $q->whereHas('form', function ($formQuery) use ($filters) {
                    $formQuery->where('event_id', $filters['event_id']);
                });
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Estadísticas por formulario
        $submissionsByForm = FormSubmission::with('form')
            ->when(isset($filters['form_id']), function ($q) use ($filters) {
                $q->where('form_id', $filters['form_id']);
            })
            ->when(isset($filters['event_id']), function ($q) use ($filters) {
                $q->whereHas('form', function ($formQuery) use ($filters) {
                    $formQuery->where('event_id', $filters['event_id']);
                });
            })
            ->when(isset($filters['date_from']), function ($q) use ($filters) {
                $q->where('submitted_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function ($q) use ($filters) {
                $q->where('submitted_at', '<=', $filters['date_to']);
            })
            ->get()
            ->groupBy('form.name')
            ->map(function ($submissions) {
                return $submissions->count();
            });

        // Estadísticas por ciudad
        $submissionsByCity = FormSubmission::with('form.event')
            ->when(isset($filters['form_id']), function ($q) use ($filters) {
                $q->where('form_id', $filters['form_id']);
            })
            ->when(isset($filters['event_id']), function ($q) use ($filters) {
                $q->whereHas('form', function ($formQuery) use ($filters) {
                    $formQuery->where('event_id', $filters['event_id']);
                });
            })
            ->when(isset($filters['date_from']), function ($q) use ($filters) {
                $q->where('submitted_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function ($q) use ($filters) {
                $q->where('submitted_at', '<=', $filters['date_to']);
            })
            ->get()
            ->groupBy(function ($submission) {
                return $submission->form && $submission->form->event ? $submission->form->event->name : 'Sin Evento';
            })
            ->map(function ($submissions) {
                return $submissions->count();
            });

        // Estadísticas por hora del día
        $submissionsByHour = FormSubmission::selectRaw('HOUR(submitted_at) as hour, COUNT(*) as count')
            ->when(isset($filters['form_id']), function ($q) use ($filters) {
                $q->where('form_id', $filters['form_id']);
            })
            ->when(isset($filters['event_id']), function ($q) use ($filters) {
                $q->whereHas('form', function ($formQuery) use ($filters) {
                    $formQuery->where('event_id', $filters['event_id']);
                });
            })
            ->when(isset($filters['date_from']), function ($q) use ($filters) {
                $q->where('submitted_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function ($q) use ($filters) {
                $q->where('submitted_at', '<=', $filters['date_to']);
            })
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Estadísticas por día de la semana
        $submissionsByDayOfWeek = FormSubmission::selectRaw('DAYOFWEEK(submitted_at) as day_of_week, COUNT(*) as count')
            ->when(isset($filters['form_id']), function ($q) use ($filters) {
                $q->where('form_id', $filters['form_id']);
            })
            ->when(isset($filters['event_id']), function ($q) use ($filters) {
                $q->whereHas('form', function ($formQuery) use ($filters) {
                    $formQuery->where('event_id', $filters['event_id']);
                });
            })
            ->when(isset($filters['date_from']), function ($q) use ($filters) {
                $q->where('submitted_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function ($q) use ($filters) {
                $q->where('submitted_at', '<=', $filters['date_to']);
            })
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();

        // Estadísticas por mes (últimos 12 meses)
        $submissionsByMonth = FormSubmission::selectRaw('YEAR(submitted_at) as year, MONTH(submitted_at) as month, COUNT(*) as count')
            ->where('submitted_at', '>=', now()->subMonths(12))
            ->when(isset($filters['form_id']), function ($q) use ($filters) {
                $q->where('form_id', $filters['form_id']);
            })
            ->when(isset($filters['event_id']), function ($q) use ($filters) {
                $q->whereHas('form', function ($formQuery) use ($filters) {
                    $formQuery->where('event_id', $filters['event_id']);
                });
            })
            ->when(isset($filters['date_from']), function ($q) use ($filters) {
                $q->where('submitted_at', '>=', $filters['date_from']);
            })
            ->when(isset($filters['date_to']), function ($q) use ($filters) {
                $q->where('submitted_at', '<=', $filters['date_to']);
            })
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Promedio de envíos por día
        $avgSubmissionsPerDay = $totalSubmissions > 0 ? 
            round($totalSubmissions / max(1, (strtotime($dateTo) - strtotime($dateFrom)) / 86400), 2) : 0;

        // Tasa de conversión (si hay datos de participantes únicos)
        $conversionRate = $uniqueParticipants > 0 ? 
            round(($totalSubmissions / $uniqueParticipants) * 100, 2) : 0;

        // Estadísticas específicas para las tarjetas de resumen
        $todaySubmissions = $this->getTodaySubmissions($filters);
        $thisWeekSubmissions = $this->getThisWeekSubmissions($filters);
        $thisMonthSubmissions = $this->getThisMonthSubmissions($filters);

        return [
            'total_submissions' => $totalSubmissions,
            'unique_participants' => $uniqueParticipants,
            'avg_submissions_per_day' => $avgSubmissionsPerDay,
            'conversion_rate' => $conversionRate,
            'submissions_by_date' => $submissionsByDate->toArray(),
            'submissions_by_form' => $submissionsByForm,
            'submissions_by_city' => $submissionsByCity,
            'submissions_by_hour' => $submissionsByHour->toArray(),
            'submissions_by_day_of_week' => $submissionsByDayOfWeek->toArray(),
            'submissions_by_month' => $submissionsByMonth->toArray(),
            'today_submissions' => $todaySubmissions,
            'this_week_submissions' => $thisWeekSubmissions,
            'this_month_submissions' => $thisMonthSubmissions,
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo
            ]
        ];
    }

    /**
     * Get submissions for today.
     */
    public function getTodaySubmissions(array $filters = []): int
    {
        $query = FormSubmission::whereDate('submitted_at', today());

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['event_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('event_id', $filters['event_id']);
            });
        }

        return $query->count();
    }

    /**
     * Get submissions for this week.
     */
    public function getThisWeekSubmissions(array $filters = []): int
    {
        $query = FormSubmission::where('submitted_at', '>=', now()->startOfWeek());

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['event_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('event_id', $filters['event_id']);
            });
        }

        return $query->count();
    }

    /**
     * Get submissions for this month.
     */
    public function getThisMonthSubmissions(array $filters = []): int
    {
        $query = FormSubmission::where('submitted_at', '>=', now()->startOfMonth());

        if (isset($filters['form_id'])) {
            $query->where('form_id', $filters['form_id']);
        }

        if (isset($filters['event_id'])) {
            $query->whereHas('form', function ($q) use ($filters) {
                $q->where('event_id', $filters['event_id']);
            });
        }

        return $query->count();
    }

    /**
     * Get recent submissions.
     */
    public function getRecent(int $limit = 10): Collection
    {
        return FormSubmission::with(['form', 'participant', 'form.event'])
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
            ->with(['form', 'participant', 'form.event'])
            ->orderBy('submitted_at', 'desc')
            ->paginate($perPage);
    }
}
