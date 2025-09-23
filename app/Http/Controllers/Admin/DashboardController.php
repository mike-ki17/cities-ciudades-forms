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

class DashboardController extends Controller
{
    public function __construct(
        private SubmissionRepository $submissionRepository
    ) {}

    /**
     * Display the admin dashboard.
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
            $event->participants_count = DB::table('participants')
                ->join('attendances', 'participants.id', '=', 'attendances.participant_id')
                ->join('cycles', 'attendances.cycle_id', '=', 'cycles.id')
                ->where('cycles.events_id', $event->id)
                ->distinct('participants.id')
                ->count();
                
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
            ->with('event')
            ->orderBy('form_submissions_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
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
}