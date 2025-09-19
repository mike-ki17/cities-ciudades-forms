<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DebugLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug login process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DEBUG LOGIN PROCESS ===');
        
        // Check if user exists
        $user = User::where('email', 'admin@example.com')->first();
        
        if (!$user) {
            $this->error('User not found!');
            return;
        }
        
        $this->info('User found: ' . $user->name);
        $this->info('Email: ' . $user->email);
        $this->info('Is Admin: ' . ($user->is_admin ? 'Yes' : 'No'));
        $this->info('Email Verified: ' . ($user->email_verified_at ? 'Yes' : 'No'));
        
        // Check password
        $passwordValid = Hash::check('password', $user->password);
        $this->info('Password valid: ' . ($passwordValid ? 'Yes' : 'No'));
        
        if (!$passwordValid) {
            $this->warn('Resetting password...');
            $user->password = Hash::make('password');
            $user->save();
            $this->info('Password reset to "password"');
        }
        
        // Test Auth::attempt
        $this->info('Testing Auth::attempt...');
        $attemptResult = Auth::attempt(['email' => 'admin@example.com', 'password' => 'password']);
        $this->info('Auth::attempt result: ' . ($attemptResult ? 'Success' : 'Failed'));
        
        if ($attemptResult) {
            $this->info('User is now authenticated: ' . Auth::user()->name);
            $this->info('Is Admin: ' . (Auth::user()->isAdmin() ? 'Yes' : 'No'));
            
            // Test admin dashboard access
            $this->info('Testing admin dashboard access...');
            if (Auth::user()->isAdmin()) {
                $this->info('✅ User can access admin dashboard');
            } else {
                $this->error('❌ User cannot access admin dashboard');
            }
            
            Auth::logout();
        }
        
        $this->info('=== END DEBUG ===');
    }
}