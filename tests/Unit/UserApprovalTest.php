<?php
namespace Tests\Unit;
use App\Models\User;
use Tests\TestCase;

class UserApprovalTest extends TestCase {
    public function test_user_is_pending_by_default(): void {
        $user = new User(['name' => 'A', 'email' => 'a@b.c']);
        $this->assertFalse($user->isApproved());
    }
    public function test_user_with_approved_at_is_approved(): void {
        $user = new User();
        $user->approved_at = now();
        $this->assertTrue($user->isApproved());
    }
}
