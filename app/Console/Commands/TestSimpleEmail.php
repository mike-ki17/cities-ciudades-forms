<?php

namespace App\Console\Commands;

use App\Mail\TestSimpleMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSimpleEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:simple-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a simple test email';

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

        $this->info("Sending simple test email to: {$email}");

        try {
            Mail::to($email)->send(new TestSimpleMail());
            
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
