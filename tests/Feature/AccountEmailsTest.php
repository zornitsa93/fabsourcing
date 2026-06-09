<?php
namespace Tests\Feature;
use App\Mail\AccountApprovedUser;
use App\Mail\AccountPendingAdmin;
use App\Mail\AccountReceivedUser;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountEmailsTest extends TestCase {
    use RefreshDatabase;

    public function test_no_email_on_approve_when_flag_disabled(): void {
        config(['documents.notifications_enabled' => false]);
        Mail::fake();
        $admin = Admin::create(['name'=>'A','email'=>'a@a.fr','password'=>bcrypt('secret123')]);
        $u = User::create(['name'=>'U','email'=>'u@a.fr','password'=>bcrypt('x')]);
        $this->actingAs($admin,'admin')->post("/admin/accounts/{$u->id}/approve");
        Mail::assertNothingSent();
    }
    public function test_approved_email_sent_when_flag_enabled(): void {
        config(['documents.notifications_enabled' => true]);
        Mail::fake();
        $admin = Admin::create(['name'=>'A','email'=>'a2@a.fr','password'=>bcrypt('secret123')]);
        $u = User::create(['name'=>'U','email'=>'u2@a.fr','password'=>bcrypt('x')]);
        $this->actingAs($admin,'admin')->post("/admin/accounts/{$u->id}/approve");
        Mail::assertSent(AccountApprovedUser::class);
    }
    public function test_registration_sends_pending_emails_when_enabled(): void {
        config(['documents.notifications_enabled' => true]);
        Mail::fake();
        $this->post('/fr/inscription', [
            'name'=>'Jean','email'=>'jean@acme.fr','company'=>'Acme','phone'=>'01',
            'password'=>'secret123','password_confirmation'=>'secret123','gdpr'=>'1',
        ]);
        Mail::assertSent(AccountReceivedUser::class);
        Mail::assertSent(AccountPendingAdmin::class);
    }
}
