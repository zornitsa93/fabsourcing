<?php
namespace Tests\Feature;
use App\Models\Admin;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDocumentsTest extends TestCase {
    use RefreshDatabase;
    private function admin(): Admin {
        return Admin::firstOrCreate(['email'=>'a@a.fr'], ['name'=>'A','password'=>bcrypt('secret123')]);
    }
    public function test_admin_uploads_a_document_to_private_disk(): void {
        Storage::fake('documents');
        $res = $this->actingAs($this->admin(), 'admin')->post('/admin/documents', [
            'title_fr'=>'Catalogue','title_en'=>'Catalogue','description_fr'=>'','description_en'=>'',
            'sort_order'=>1,'published'=>1,
            'file'=> UploadedFile::fake()->create('cat.pdf', 100, 'application/pdf'),
        ]);
        $res->assertRedirect();
        $doc = Document::first();
        $this->assertNotNull($doc);
        $this->assertSame('Catalogue', $doc->getTranslation('title','fr'));
        Storage::disk('documents')->assertExists($doc->file_path);
    }
    public function test_admin_deletes_a_document_and_its_file(): void {
        Storage::fake('documents');
        $this->actingAs($this->admin(), 'admin')->post('/admin/documents', [
            'title_fr'=>'X','title_en'=>'X','sort_order'=>0,'published'=>1,
            'file'=> UploadedFile::fake()->create('x.pdf', 10, 'application/pdf'),
        ]);
        $doc = Document::first();
        $path = $doc->file_path;
        $this->actingAs($this->admin(), 'admin')->delete("/admin/documents/{$doc->id}")->assertRedirect();
        $this->assertNull(Document::find($doc->id));
        Storage::disk('documents')->assertMissing($path);
    }
}
