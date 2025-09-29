<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSmtpEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smtp-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email using SMTP';

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

        $this->info("Sending test email to: {$email}");

        try {
            // Use SMTP mailer specifically
            Mail::mailer('smtp')->to($email)->send(new TestEmail());
            
            $this->info('✅ Email sent successfully!');
            $this->info('Check your inbox and spam folder.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            $this->error('Error details: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
