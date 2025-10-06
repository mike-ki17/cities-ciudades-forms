<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\MetricsController;
use App\Repositories\SubmissionRepository;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestMetricsView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:metrics-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the metrics view data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing metrics view data...');
        
        // Test the controller method
        $controller = new MetricsController(new SubmissionRepository());
        $request = new Request();
        
        try {
            $view = $controller->statistics($request);
            $data = $view->getData();
            
            $this->info('View data keys: ' . implode(', ', array_keys($data)));
            
            if (isset($data['statistics'])) {
                $statistics = $data['statistics'];
                $this->info('Statistics keys: ' . implode(', ', array_keys($statistics)));
                
                if (isset($statistics['submissions_by_date'])) {
                    $submissionsByDate = $statistics['submissions_by_date'];
                    $this->info('Submissions by date count: ' . (is_array($submissionsByDate) ? count($submissionsByDate) : 'not array'));
                    
                    if (is_array($submissionsByDate) && count($submissionsByDate) > 0) {
                        $this->info('Sample data:');
                        foreach (array_slice($submissionsByDate, 0, 3) as $item) {
                            $this->line('  ' . json_encode($item));
                        }
                    }
                } else {
                    $this->warn('submissions_by_date not found in statistics');
                }
            } else {
                $this->warn('statistics not found in view data');
            }
            
        } catch (\Exception $e) {
            $this->error('Error testing metrics view: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
