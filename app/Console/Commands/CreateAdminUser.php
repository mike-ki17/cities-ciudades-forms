<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--email=admin@example.com} {--password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->info("User with email {$email} already exists.");
            
            // Update password if needed
            $user = User::where('email', $email)->first();
            $user->password = Hash::make($password);
            $user->is_admin = true;
            $user->save();
            
            $this->info("Password updated for user: {$email}");
        } else {
            // Create new admin user
            User::create([
                'name' => 'Administrador',
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);
            
            $this->info("Admin user created successfully!");
        }

        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("You can now login at: /login");
    }
}