<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CheckAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check admin user credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'admin@example.com')->first();
        
        if (!$user) {
            $this->error('Admin user not found!');
            return;
        }

        $this->info('Admin user found:');
        $this->info('ID: ' . $user->id);
        $this->info('Name: ' . $user->name);
        $this->info('Email: ' . $user->email);
        $this->info('Is Admin: ' . ($user->is_admin ? 'Yes' : 'No'));
        $this->info('Email Verified: ' . ($user->email_verified_at ? 'Yes' : 'No'));
        
        // Test password
        $passwordCheck = Hash::check('password', $user->password);
        $this->info('Password check (password): ' . ($passwordCheck ? 'Valid' : 'Invalid'));
        
        if (!$passwordCheck) {
            $this->warn('Password is not "password". Trying to reset...');
            $user->password = Hash::make('password');
            $user->save();
            $this->info('Password reset to "password"');
        }
    }
}