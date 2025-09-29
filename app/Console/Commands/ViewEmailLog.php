<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewEmailLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:view-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View the latest email from the log file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->error('Log file not found: ' . $logFile);
            return 1;
        }

        $this->info('📄 Reading email from log file...');
        $this->info('Log file: ' . $logFile);
        $this->newLine();

        // Read the last 50 lines of the log file
        $lines = file($logFile);
        $lastLines = array_slice($lines, -50);
        
        $emailFound = false;
        $emailContent = [];
        
        foreach ($lastLines as $line) {
            if (strpos($line, 'Message-ID:') !== false || strpos($line, 'To:') !== false || strpos($line, 'Subject:') !== false) {
                $emailFound = true;
            }
            
            if ($emailFound) {
                $emailContent[] = $line;
            }
        }
        
        if (empty($emailContent)) {
            $this->warn('No email found in the recent log entries.');
            $this->info('Try running: php artisan test:local-email your-email@example.com');
            return 0;
        }
        
        $this->info('📧 Email found in log:');
        $this->newLine();
        
        foreach ($emailContent as $line) {
            $this->line(trim($line));
        }
        
        return 0;
    }
}
