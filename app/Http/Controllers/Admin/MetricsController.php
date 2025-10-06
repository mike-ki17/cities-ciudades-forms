<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Repositories\SubmissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MetricsController extends Controller
{
    public function __construct(
        private SubmissionRepository $submissionRepository
    ) {}

    /**
     * Display metrics dashboard for restricted users.
     */
    public function index(): View
    {
        // Get basic statistics
        $totalEvents = Event::count();
        $totalForms = Form::count();
        $activeForms = Form::active()->count();
        $totalParticipants = Participant::count();
        $totalSubmissions = FormSubmission::count();
        $totalCities = Event::distinct('city')->count('city');

        // Get recent submissions
        $recentSubmissions = $this->submissionRepository->getRecent(10);

        // Get submissions by event
        $submissionsByEvent = Event::withCount(['forms' => function ($query) {
            $query->where('is_active', true);
        }])
        ->get()
        ->map(function ($event) {
            $event->submissions_count = FormSubmission::whereHas('form', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })->count();
            
            // Calculate participants count through cycles and attendances
            try {
                $event->participants_count = DB::table('participants')
                    ->join('attendances', 'participants.id', '=', 'attendances.participant_id')
                    ->join('cycles', 'attendances.cycle_id', '=', 'cycles.id')
                    ->where('cycles.events_id', $event->id)
                    ->distinct('participants.id')
                    ->count();
            } catch (\Exception $e) {
                // Fallback: count participants who have submitted forms for this event
                $event->participants_count = DB::table('participants')
                    ->join('form_submissions', 'participants.id', '=', 'form_submissions.participant_id')
                    ->join('forms', 'form_submissions.form_id', '=', 'forms.id')
                    ->where('forms.event_id', $event->id)
                    ->distinct('participants.id')
                    ->count();
            }
                
            return $event;
        });

        // Get submissions by date (last 30 days)
        $submissionsByDate = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->where('submitted_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top forms by submissions
        $topForms = Form::withCount('formSubmissions')
            ->orderBy('form_submissions_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.metrics.index', compact(
            'totalEvents',
            'totalForms', 
            'activeForms',
            'totalParticipants',
            'totalSubmissions',
            'totalCities',
            'recentSubmissions',
            'submissionsByEvent',
            'submissionsByDate',
            'topForms'
        ));
    }

    /**
     * Display detailed statistics for restricted users.
     */
    public function statistics(Request $request): View
    {
        $filters = $request->only(['form_id', 'event_id', 'date_from', 'date_to']);
        
        try {
            $statistics = $this->submissionRepository->getStatistics($filters);
            
            // Debug: Log the statistics data
            \Log::info('Statistics data:', [
                'submissions_by_date_count' => is_array($statistics['submissions_by_date']) ? count($statistics['submissions_by_date']) : $statistics['submissions_by_date']->count(),
                'submissions_by_date_sample' => is_array($statistics['submissions_by_date']) ? array_slice($statistics['submissions_by_date'], 0, 3) : $statistics['submissions_by_date']->take(3)->toArray(),
                'total_submissions' => $statistics['total_submissions']
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting statistics: ' . $e->getMessage());
            
            // Si hay un error, crear estadísticas vacías
            $statistics = [
                'total_submissions' => 0,
                'unique_participants' => 0,
                'avg_submissions_per_day' => 0,
                'conversion_rate' => 0,
                'submissions_by_date' => collect(),
                'submissions_by_form' => collect(),
                'submissions_by_city' => collect(),
                'submissions_by_hour' => collect(),
                'submissions_by_day_of_week' => collect(),
                'submissions_by_month' => collect(),
                'date_range' => [
                    'from' => $filters['date_from'] ?? now()->subDays(30)->format('Y-m-d'),
                    'to' => $filters['date_to'] ?? now()->format('Y-m-d')
                ]
            ];
        }
        
        $forms = Form::with('event')->orderBy('name')->get();
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();

        return view('admin.metrics.statistics', compact('statistics', 'forms', 'events', 'filters'));
    }
}
