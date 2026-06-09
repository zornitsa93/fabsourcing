<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase {
    use RefreshDatabase;
    public function test_registration_creates_a_pending_user(): void {
        $res = $this->post('/fr/inscription', [
            'name' => 'Jean Dupont', 'email' => 'jean@acme.fr', 'company' => 'Acme',
            'phone' => '0102', 'password' => 'secret123', 'password_confirmation' => 'secret123',
            'gdpr' => '1',
        ]);
        $res->assertRedirect();
        $user = User::where('email', 'jean@acme.fr')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->approved_at);
        $this->assertNotNull($user->gdpr_consent_at);
        $this->assertGuest();
    }
    public function test_registration_requires_gdpr_and_unique_email(): void {
        User::create(['name'=>'x','email'=>'dupe@acme.fr','password'=>bcrypt('x')]);
        $this->post('/fr/inscription', [
            'name'=>'Y','email'=>'dupe@acme.fr','password'=>'secret123','password_confirmation'=>'secret123',
        ])->assertSessionHasErrors(['email','gdpr']);
    }
}
