<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\WebPagesController;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentsController extends WebPagesController {
    public function index(string $lang): View {
        $documents = Document::published()->ordered()->get();
        return view('web.documents.index', array_merge(
            $this->commonForWebPages($lang),
            compact('documents')
        ));
    }
    public function download(string $lang, Document $document): StreamedResponse {
        abort_unless($document->published, 404);
        abort_unless(Storage::disk('documents')->exists($document->file_path), 404);
        $document->increment('download_count');
        return Storage::disk('documents')->download($document->file_path, $document->original_filename);
    }
}
