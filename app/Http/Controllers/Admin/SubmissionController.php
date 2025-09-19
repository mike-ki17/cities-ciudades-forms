<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Repositories\SubmissionRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function __construct(
        private SubmissionRepository $submissionRepository
    ) {}

    /**
     * Display a listing of submissions.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['form_id', 'city_id', 'date_from', 'date_to', 'search']);
        
        $submissions = $this->submissionRepository->getWithFilters($filters);
        
        $forms = Form::with('city')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('admin.submissions.index', compact('submissions', 'forms', 'cities', 'filters'));
    }

    /**
     * Display the specified submission.
     */
    public function show(FormSubmission $submission): View
    {
        $submission->load(['form', 'participant', 'form.city']);
        
        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Export submissions to CSV.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['form_id', 'city_id', 'date_from', 'date_to']);
        
        $submissions = $this->submissionRepository->getWithFilters($filters, 1000);
        
        $filename = 'submissions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Fecha de Envío',
                'Formulario',
                'Ciudad',
                'Participante',
                'Email',
                'Datos del Formulario'
            ]);

            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->id,
                    $submission->submitted_at->format('Y-m-d H:i:s'),
                    $submission->form->name,
                    $submission->form->city?->name ?? 'General',
                    $submission->participant->full_name,
                    $submission->participant->email,
                    json_encode($submission->data_json, JSON_UNESCAPED_UNICODE)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get submission statistics.
     */
    public function statistics(Request $request): View
    {
        $filters = $request->only(['form_id', 'city_id', 'date_from', 'date_to']);
        
        $statistics = $this->submissionRepository->getStatistics($filters);
        
        $forms = Form::with('city')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        return view('admin.submissions.statistics', compact('statistics', 'forms', 'cities', 'filters'));
    }
}