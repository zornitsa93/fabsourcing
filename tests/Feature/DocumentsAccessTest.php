<?php
namespace Tests\Feature;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentsAccessTest extends TestCase {
    use RefreshDatabase;

    private function approvedUser(): User {
        $u = User::create(['name'=>'U','email'=>'ok@a.fr','password'=>bcrypt('secret123')]);
        return $u->forceFill(['approved_at' => now()])->save() ? $u : $u;
    }

    public function test_guest_is_redirected_to_login(): void {
        $this->get('/fr/documents')->assertRedirect('/fr/connexion');
    }
    public function test_pending_user_redirected(): void {
        $u = User::create(['name'=>'U','email'=>'p@a.fr','password'=>bcrypt('secret123')]); // approved_at null
        $this->actingAs($u)->get('/fr/documents')->assertRedirect('/fr/connexion');
    }
    public function test_approved_user_sees_list_and_downloads(): void {
        Storage::fake('documents');
        Storage::disk('documents')->put('documents/cat.pdf', '%PDF-1.4 test');
        $doc = Document::create([
            'title'=>['fr'=>'Catalogue','en'=>'Catalogue'], 'file_path'=>'documents/cat.pdf',
            'original_filename'=>'catalogue.pdf','published'=>true,'mime_type'=>'application/pdf',
        ]);
        $u = $this->approvedUser();
        $this->actingAs($u)->get('/fr/documents')->assertOk()->assertSee('Catalogue');
        $this->actingAs($u)->get("/fr/documents/{$doc->id}/telecharger")
            ->assertOk()->assertHeader('content-disposition', 'attachment; filename=catalogue.pdf');
        $this->assertSame(1, $doc->fresh()->download_count);
    }
}
