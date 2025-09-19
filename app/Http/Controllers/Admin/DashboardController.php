<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Repositories\SubmissionRepository;
use Illuminate\Http\Request;
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
        $totalCities = City::count();
        $totalForms = Form::count();
        $activeForms = Form::active()->count();
        $totalParticipants = Participant::count();
        $totalSubmissions = FormSubmission::count();

        // Get recent submissions
        $recentSubmissions = $this->submissionRepository->getRecent(10);

        // Get submissions by city
        $submissionsByCity = City::withCount(['forms' => function ($query) {
            $query->where('is_active', true);
        }])
        ->withCount(['participants'])
        ->get()
        ->map(function ($city) {
            $city->submissions_count = FormSubmission::whereHas('form', function ($query) use ($city) {
                $query->where('city_id', $city->id);
            })->count();
            return $city;
        });

        // Get submissions by date (last 30 days)
        $submissionsByDate = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->where('submitted_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get top forms by submissions
        $topForms = Form::withCount('formSubmissions')
            ->with('city')
            ->orderBy('form_submissions_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCities',
            'totalForms',
            'activeForms',
            'totalParticipants',
            'totalSubmissions',
            'recentSubmissions',
            'submissionsByCity',
            'submissionsByDate',
            'topForms'
        ));
    }
}