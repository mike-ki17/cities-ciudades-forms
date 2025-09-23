<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
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
        $filters = $request->only(['form_id', 'event_id', 'date_from', 'date_to', 'search']);
        
        $submissions = $this->submissionRepository->getWithFilters($filters);
        
        $forms = Form::with('event')->orderBy('name')->get();
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();

        return view('admin.submissions.index', compact('submissions', 'forms', 'events', 'filters'));
    }

    /**
     * Display the specified submission.
     */
    public function show(FormSubmission $submission): View
    {
        $submission->load(['form', 'participant', 'form.event']);
        
        // Verificar que el participante existe
        if (!$submission->participant) {
            abort(404, 'Participante no encontrado para esta submission.');
        }
        
        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Export submissions to CSV.
     */
    public function export(Request $request)
    {
        $filters = $request->only(['form_id', 'event_id', 'date_from', 'date_to']);
        
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
                'Evento',
                'Participante',
                'Email',
                'Datos del Formulario'
            ]);

            foreach ($submissions as $submission) {
                fputcsv($file, [
                    $submission->id,
                    $submission->submitted_at->format('Y-m-d H:i:s'),
                    $submission->form->name,
                    $submission->form->event?->full_name ?? 'General',
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
        $filters = $request->only(['form_id', 'event_id', 'date_from', 'date_to']);
        
        try {
            $statistics = $this->submissionRepository->getStatistics($filters);
        } catch (\Exception $e) {
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
                'date_range' => [
                    'from' => $filters['date_from'] ?? now()->subDays(30)->format('Y-m-d'),
                    'to' => $filters['date_to'] ?? now()->format('Y-m-d')
                ]
            ];
        }
        
        $forms = Form::with('event')->orderBy('name')->get();
        $events = Event::orderBy('name')->orderBy('city')->orderBy('year')->get();

        return view('admin.submissions.statistics', compact('statistics', 'forms', 'events', 'filters'));
    }
}