<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:login {email=admin@example.com} {password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test login credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("Testing login for: {$email}");

        // Find user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('User not found!');
            return;
        }

        $this->info('User found: ' . $user->name);
        $this->info('Is Admin: ' . ($user->is_admin ? 'Yes' : 'No'));

        // Test password
        $passwordValid = Hash::check($password, $user->password);
        $this->info('Password valid: ' . ($passwordValid ? 'Yes' : 'No'));

        if (!$passwordValid) {
            $this->warn('Password is invalid. Resetting to "password"...');
            $user->password = Hash::make('password');
            $user->save();
            $this->info('Password reset to "password"');
        }

        // Test Auth::attempt
        $attemptResult = Auth::attempt(['email' => $email, 'password' => $password]);
        $this->info('Auth::attempt result: ' . ($attemptResult ? 'Success' : 'Failed'));

        if ($attemptResult) {
            $this->info('Login successful!');
            Auth::logout();
        } else {
            $this->error('Login failed!');
        }
    }
}