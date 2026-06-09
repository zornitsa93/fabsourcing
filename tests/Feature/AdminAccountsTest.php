<?php
namespace Tests\Feature;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccountsTest extends TestCase {
    use RefreshDatabase;
    private function admin(string $email='adm@a.fr'): Admin {
        return Admin::create(['name'=>'Adm','email'=>$email,'password'=>bcrypt('secret123')]);
    }
    public function test_admin_can_approve_a_pending_user(): void {
        $admin = $this->admin();
        $u = User::create(['name'=>'U','email'=>'u@a.fr','password'=>bcrypt('x')]); // pending
        $this->actingAs($admin, 'admin')->post("/admin/accounts/{$u->id}/approve")->assertRedirect();
        $u->refresh();
        $this->assertNotNull($u->approved_at);
        $this->assertSame($admin->id, $u->approved_by);
    }
    public function test_admin_can_revoke_approval(): void {
        $admin = $this->admin('adm2@a.fr');
        $u = User::create(['name'=>'U','email'=>'u2@a.fr','password'=>bcrypt('x')]);
        $u->forceFill(['approved_at'=>now(),'approved_by'=>$admin->id])->save();
        $this->actingAs($admin,'admin')->post("/admin/accounts/{$u->id}/revoke")->assertRedirect();
        $this->assertNull($u->fresh()->approved_at);
    }
    public function test_index_lists_pending_and_approved(): void {
        $admin = $this->admin('adm3@a.fr');
        User::create(['name'=>'Pend','email'=>'pend@a.fr','password'=>bcrypt('x')]);
        $this->actingAs($admin,'admin')->get('/admin/accounts')->assertOk()->assertSee('pend@a.fr');
    }
}
