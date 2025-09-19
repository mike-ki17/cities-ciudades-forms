<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_form()
    {
        $response = $this->get('/form/test-form');
        
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_form()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/form/test-form');
        
        // Should not redirect to login (form might not exist, but auth should work)
        $response->assertStatus(404); // Form doesn't exist, but auth passed
    }

    public function test_user_model_has_submitted_form_method()
    {
        $user = User::factory()->create();
        
        // Test that the method exists and returns false for non-existent form
        $this->assertFalse($user->hasSubmittedForm(999));
    }
}
