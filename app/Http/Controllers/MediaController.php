<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search', '');

        $items = Media::query()
            ->when($search, fn($q) => $q->where('original_name', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(48)
            ->withQueryString();

        return view('admin.media.index', compact('items', 'search'));
    }

    /** AJAX: returns JSON list for the picker modal */
    public function pickerData(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $items  = Media::query()
            ->when($search, fn($q) => $q->where('original_name', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->limit(80)
            ->get()
            ->map(fn($m) => [
                'id'        => $m->id,
                'path'      => $m->path,
                'url'       => $m->url,
                'thumb_url' => $m->thumb_url,
                'name'      => $m->original_name,
                'alt_fr'    => $m->getTranslation('alt_text', 'fr', false) ?? '',
                'alt_en'    => $m->getTranslation('alt_text', 'en', false) ?? '',
            ]);

        return response()->json($items);
    }

    /** AJAX: show image details + usages */
    public function show(Media $medium): JsonResponse
    {
        return response()->json([
            'id'             => $medium->id,
            'path'           => $medium->path,
            'url'            => $medium->url,
            'thumb_url'      => $medium->thumb_url,
            'original_name'  => $medium->original_name,
            'mime_type'      => $medium->mime_type,
            'size_formatted' => $medium->size_formatted,
            'width'          => $medium->width,
            'height'         => $medium->height,
            'alt_fr'         => $medium->getTranslation('alt_text', 'fr', false) ?? '',
            'alt_en'         => $medium->getTranslation('alt_text', 'en', false) ?? '',
            'usages'         => $medium->findUsages(),
        ]);
    }

    /** AJAX: update alt text */
    public function update(Request $request, Media $medium): JsonResponse
    {
        $request->validate([
            'alt_fr' => 'nullable|string|max:255',
            'alt_en' => 'nullable|string|max:255',
        ]);
        $medium->setTranslation('alt_text', 'fr', $request->input('alt_fr', ''));
        $medium->setTranslation('alt_text', 'en', $request->input('alt_en', ''));
        $medium->save();
        return response()->json(['ok' => true]);
    }

    /** AJAX multi-upload */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'required|image|max:8192',
        ]);

        $uploaded = [];
        foreach ($request->file('files') as $file) {
            $ext      = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
            $filename = uniqid('media_', true) . '.' . $ext;
            $folder   = 'media';

            Storage::disk('public')->makeDirectory($folder);
            $file->storeAs($folder, $filename, 'public');

            $path    = $folder . '/' . $filename;
            $srcPath = Storage::disk('public')->path($path);
            $base    = pathinfo($filename, PATHINFO_FILENAME);

            [$w, $h] = @getimagesize($srcPath) ?: [null, null];

            $this->generateResize($srcPath, $folder . '/' . $base . '_thumb.' . $ext, 300, 300, true);
            $this->generateResize($srcPath, $folder . '/' . $base . '_medium.' . $ext, 800, 600, false);

            $record = Media::create([
                'filename'      => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size_bytes'    => $file->getSize(),
                'width'         => $w,
                'height'        => $h,
            ]);

            $uploaded[] = [
                'id'        => $record->id,
                'path'      => $record->path,
                'url'       => $record->url,
                'thumb_url' => $record->thumb_url,
                'name'      => $record->original_name,
            ];
        }

        return response()->json(['uploaded' => $uploaded]);
    }

    /** AJAX delete */
    public function destroy(Media $medium): JsonResponse
    {
        $usages = $medium->findUsages();
        if (!empty($usages)) {
            return response()->json([
                'error'  => 'Изображението се използва и не може да бъде изтрито.',
                'usages' => $usages,
            ], 422);
        }

        $base = pathinfo($medium->path, PATHINFO_FILENAME);
        $dir  = pathinfo($medium->path, PATHINFO_DIRNAME);
        $ext  = pathinfo($medium->path, PATHINFO_EXTENSION);

        Storage::disk('public')->delete($medium->path);
        Storage::disk('public')->delete($dir . '/' . $base . '_thumb.' . $ext);
        Storage::disk('public')->delete($dir . '/' . $base . '_medium.' . $ext);

        $medium->delete();
        return response()->json(['ok' => true]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function generateResize(string $srcPath, string $destRel, int $w, int $h, bool $cover): void
    {
        try {
            $manager = new ImageManager(new Driver());
            $img     = $manager->read($srcPath);
            $cover ? $img->cover($w, $h) : $img->scaleDown($w, $h);
            $img->save(Storage::disk('public')->path($destRel));
        } catch (\Throwable) {}
    }
}
