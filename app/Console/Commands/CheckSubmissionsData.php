<?php

namespace App\Console\Commands;

use App\Models\FormSubmission;
use Illuminate\Console\Command;

class CheckSubmissionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:submissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check submissions data for debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking submissions data...');
        
        $totalSubmissions = FormSubmission::count();
        $this->info("Total submissions: {$totalSubmissions}");
        
        if ($totalSubmissions > 0) {
            $recentSubmissions = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
                ->where('submitted_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                
            $this->info("Recent submissions by date (last 30 days):");
            foreach ($recentSubmissions as $item) {
                $this->line("  {$item->date}: {$item->count}");
            }
            
            if ($recentSubmissions->isEmpty()) {
                $this->warn("No submissions in the last 30 days!");
                
                // Check if there are any submissions at all
                $allSubmissions = FormSubmission::selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                    
                if ($allSubmissions->isNotEmpty()) {
                    $this->info("All submissions by date:");
                    foreach ($allSubmissions as $item) {
                        $this->line("  {$item->date}: {$item->count}");
                    }
                } else {
                    $this->error("No submissions found in the database!");
                }
            }
        } else {
            $this->error("No submissions found in the database!");
        }
    }
}
