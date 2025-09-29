<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestGmailEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:gmail-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email using Gmail SMTP';

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

        $this->info("📧 Sending test email to: {$email}");
        $this->info("🔧 Using Gmail SMTP configuration...");

        try {
            Mail::to($email)->send(new TestEmail());
            
            $this->info('✅ Email sent successfully!');
            $this->info('📬 Check your inbox and spam folder.');
            $this->info('⏰ It may take a few minutes to arrive.');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'Authentication failed') !== false) {
                $this->error('🔑 Authentication failed. Check your Gmail credentials:');
                $this->error('   - Make sure 2FA is enabled on your Google account');
                $this->error('   - Use an App Password, not your regular password');
                $this->error('   - Check MAIL_USERNAME and MAIL_PASSWORD in .env');
            }
            
            return 1;
        }
    }
}
