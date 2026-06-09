<?php
namespace Tests\Unit;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentModelTest extends TestCase {
    use RefreshDatabase;
    public function test_translatable_title_and_published_scope(): void {
        $doc = new Document();
        $doc->setTranslation('title', 'fr', 'Catalogue');
        $doc->setTranslation('title', 'en', 'Catalogue EN');
        $doc->file_path = 'documents/x.pdf';
        $doc->original_filename = 'x.pdf';
        $doc->published = false;
        $doc->save();
        Document::create([
            'title' => ['fr' => 'B', 'en' => 'B'],
            'file_path' => 'documents/y.pdf', 'original_filename' => 'y.pdf', 'published' => true,
        ]);
        $this->assertSame('Catalogue', $doc->fresh()->getTranslation('title', 'fr'));
        $this->assertCount(1, Document::published()->get());
    }
}
