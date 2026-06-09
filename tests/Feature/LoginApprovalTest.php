<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginApprovalTest extends TestCase {
    use RefreshDatabase;
    private function user(bool $approved): User {
        return User::create([
            'name'=>'U','email'=>'u@acme.fr','password'=>Hash::make('secret123'),
            'approved_at'=> $approved ? now() : null,
        ]);
    }
    public function test_pending_user_cannot_log_in(): void {
        $this->user(false);
        $this->post('/fr/connexion', ['email'=>'u@acme.fr','password'=>'secret123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
    public function test_approved_user_can_log_in(): void {
        $this->user(true);
        $this->post('/fr/connexion', ['email'=>'u@acme.fr','password'=>'secret123'])
            ->assertRedirect();
        $this->assertAuthenticated();
    }
}
