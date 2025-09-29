<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestLocalEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:local-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email using log driver for local testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return 1;
        }

        $this->info("Sending test email to: {$email} (using log driver)");

        try {
            // Use log mailer specifically for local testing
            Mail::mailer('log')->to($email)->send(new TestEmail());
            
            $this->info('✅ Email sent successfully to log!');
            $this->info('📄 Check the log file: storage/logs/laravel.log');
            $this->info('📧 The email content has been saved to the log file.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            $this->error('Error details: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
