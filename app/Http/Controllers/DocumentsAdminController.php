<?php
namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentsAdminController extends Controller {
    public function index(): View {
        return view('admin.documents.index', ['documents' => Document::ordered()->get()]);
    }
    public function create(): View { return view('admin.documents.create'); }

    public function store(Request $request): RedirectResponse {
        $data = $this->validateData($request, true);
        $doc = new Document();
        $this->fill($doc, $data);
        $this->attachFile($doc, $request);
        $doc->save();
        return redirect()->route('documents.index')->with('status', 'created');
    }
    public function edit(Document $document): View {
        return view('admin.documents.edit', ['document' => $document]);
    }
    public function update(Request $request, Document $document): RedirectResponse {
        $data = $this->validateData($request, false);
        $this->fill($document, $data);
        if ($request->hasFile('file')) {
            Storage::disk('documents')->delete($document->file_path);
            $this->attachFile($document, $request);
        }
        $document->save();
        return redirect()->route('documents.index')->with('status', 'updated');
    }
    public function destroy(Document $document): RedirectResponse {
        Storage::disk('documents')->delete($document->file_path);
        $document->delete();
        return redirect()->route('documents.index')->with('status', 'deleted');
    }

    private function validateData(Request $request, bool $fileRequired): array {
        return $request->validate([
            'title_fr'      => ['required','string','max:190'],
            'title_en'      => ['required','string','max:190'],
            'description_fr'=> ['nullable','string'],
            'description_en'=> ['nullable','string'],
            'sort_order'    => ['nullable','integer','min:0'],
            'published'     => ['nullable','boolean'],
            'file'          => [$fileRequired ? 'required' : 'nullable','file','mimes:pdf','max:'.config('documents.max_upload_kb')],
        ]);
    }
    private function fill(Document $doc, array $data): void {
        $doc->setTranslation('title','fr',$data['title_fr']);
        $doc->setTranslation('title','en',$data['title_en']);
        $doc->setTranslation('description','fr',$data['description_fr'] ?? '');
        $doc->setTranslation('description','en',$data['description_en'] ?? '');
        $doc->sort_order = $data['sort_order'] ?? 0;
        $doc->published  = (bool)($data['published'] ?? false);
    }
    private function attachFile(Document $doc, Request $request): void {
        $file = $request->file('file');
        $path = $file->store('documents', 'documents');
        $doc->file_path = $path;
        $doc->original_filename = $file->getClientOriginalName();
        $doc->file_size = $file->getSize();
        $doc->mime_type = $file->getMimeType();
    }
}
