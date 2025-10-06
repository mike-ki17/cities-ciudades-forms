<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckUserTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user table structure and data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking user table structure...');
        
        $columns = Schema::getColumnListing('users');
        $this->info('Users table columns: ' . implode(', ', $columns));
        
        if (in_array('role', $columns)) {
            $this->info('✅ Role column exists');
        } else {
            $this->warn('❌ Role column does not exist');
        }
        
        $this->info('Checking users...');
        $users = User::all();
        
        foreach ($users as $user) {
            $role = $user->role ?? 'not set';
            $this->line("User: {$user->name} ({$user->email}) - Role: {$role}");
        }
    }
}
