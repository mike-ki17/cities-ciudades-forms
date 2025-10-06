<?php

namespace App\Console\Commands;

use App\Models\FormSubmission;
use App\Repositories\SubmissionRepository;
use Illuminate\Console\Command;

class TestDateQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:date-query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the date query for submissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing date query...');
        
        // Test the exact query from the repository
        $dateFrom = now()->subDays(30)->format('Y-m-d');
        $dateTo = now()->format('Y-m-d');
        
        $this->info("Date range: {$dateFrom} to {$dateTo}");
        
        $submissionsByDate = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
            ->whereBetween('submitted_at', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $this->info("Query result count: " . $submissionsByDate->count());
        
        if ($submissionsByDate->count() > 0) {
            $this->info("Data structure:");
            foreach ($submissionsByDate as $item) {
                $this->line("  Date: {$item->date}, Count: {$item->count}");
            }
            
            $this->info("Array format:");
            $arrayData = $submissionsByDate->toArray();
            foreach ($arrayData as $item) {
                $this->line("  " . json_encode($item));
            }
        } else {
            $this->warn("No data found in the date range!");
            
            // Check if there are any submissions at all
            $totalSubmissions = FormSubmission::count();
            $this->info("Total submissions in database: {$totalSubmissions}");
            
            if ($totalSubmissions > 0) {
                $allDates = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                    
                $this->info("All submission dates:");
                foreach ($allDates as $item) {
                    $this->line("  {$item->date}: {$item->count}");
                }
            }
        }
        
        // Test the repository method
        $this->info("\nTesting repository method...");
        $repository = new SubmissionRepository();
        $statistics = $repository->getStatistics([]);
        
        $this->info("Repository result - submissions_by_date:");
        if (is_array($statistics['submissions_by_date'])) {
            $this->info("Count: " . count($statistics['submissions_by_date']));
            foreach ($statistics['submissions_by_date'] as $item) {
                $this->line("  " . json_encode($item));
            }
        } else {
            $this->info("Not an array, type: " . gettype($statistics['submissions_by_date']));
        }
    }
}
