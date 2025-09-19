<?php

namespace App\Console\Commands;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Participant;
use App\Models\City;
use App\Models\User;
use Illuminate\Console\Command;

class TestModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test all models to ensure they work correctly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing all models...');
        
        try {
            // Test City model
            $this->info('Testing City model...');
            $cities = City::count();
            $this->info("Cities found: {$cities}");
            
            // Test Form model
            $this->info('Testing Form model...');
            $forms = Form::count();
            $this->info("Forms found: {$forms}");
            
            // Test Participant model
            $this->info('Testing Participant model...');
            $participants = Participant::count();
            $this->info("Participants found: {$participants}");
            
            // Test FormSubmission model
            $this->info('Testing FormSubmission model...');
            $submissions = FormSubmission::count();
            $this->info("Form submissions found: {$submissions}");
            
            // Test User model
            $this->info('Testing User model...');
            $users = User::count();
            $this->info("Users found: {$users}");
            
            $this->info('✅ All models are working correctly!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error testing models: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}