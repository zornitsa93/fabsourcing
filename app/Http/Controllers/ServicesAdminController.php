<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServicesAdminController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('sort_order')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title.fr'            => 'required|string|max:150',
            'title.en'            => 'nullable|string|max:150',
            'description.fr'      => 'required|string|max:300',
            'description.en'      => 'nullable|string|max:300',
            'long_description.fr' => 'nullable|string',
            'long_description.en' => 'nullable|string',
            'slug'                => 'required|string|alpha_dash|max:120|unique:services,slug',
            'number'              => 'nullable|string|max:10',
            'col_span'            => 'required|integer|min:1|max:12',
            'sort_order'          => 'nullable|integer|min:0',
            'image'               => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $service = Service::create([
            'slug'       => $request->input('slug'),
            'number'     => $request->input('number', ''),
            'col_span'   => $request->integer('col_span', 4),
            'featured'   => $request->boolean('featured'),
            'published'  => $request->boolean('published'),
            'sort_order' => $request->integer('sort_order', Service::max('sort_order') + 1),
        ]);

        foreach (['title', 'description', 'long_description'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $service->setTranslation($field, $locale, $value);
                }
            }
        }

        if ($request->hasFile('image')) {
            $service->image = $this->storeServiceImage($request->file('image'), $service->slug);
        }

        $service->save();

        return redirect()->route('services-admin.index')
            ->with('success', 'Услугата е създадена.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $request->validate([
            'title.fr'            => 'required|string|max:150',
            'title.en'            => 'nullable|string|max:150',
            'description.fr'      => 'required|string|max:300',
            'description.en'      => 'nullable|string|max:300',
            'long_description.fr' => 'nullable|string',
            'long_description.en' => 'nullable|string',
            'slug'                => 'required|string|alpha_dash|max:120|unique:services,slug,' . $service->id,
            'number'              => 'nullable|string|max:10',
            'col_span'            => 'required|integer|min:1|max:12',
            'sort_order'          => 'nullable|integer|min:0',
            'image'               => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $service->update([
            'slug'       => $request->input('slug'),
            'number'     => $request->input('number', ''),
            'col_span'   => $request->integer('col_span', 4),
            'featured'   => $request->boolean('featured'),
            'published'  => $request->boolean('published'),
            'sort_order' => $request->integer('sort_order', $service->sort_order),
        ]);

        foreach (['title', 'description', 'long_description'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $service->setTranslation($field, $locale, $value);
                }
            }
        }

        if ($request->boolean('remove_image') && $service->image) {
            $this->deleteServiceImage($service->image);
            $service->image = null;
        } elseif ($request->hasFile('image')) {
            if ($service->image) {
                $this->deleteServiceImage($service->image);
            }
            $service->image = $this->storeServiceImage($request->file('image'), $service->slug);
        }

        $service->save();

        return redirect()->route('services-admin.index')
            ->with('success', 'Услугата е обновена.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete(); // model booted() handles image deletion
        return redirect()->route('services-admin.index')
            ->with('success', 'Услугата е изтрита.');
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        foreach ($request->input('order', []) as $position => $id) {
            Service::where('id', $id)->update(['sort_order' => $position + 1]);
        }
        return response()->json(['ok' => true]);
    }

    private function storeServiceImage(\Illuminate\Http\UploadedFile $file, string $slug): string
    {
        $ext    = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $name   = Str::slug($slug) . '-' . time() . '.' . $ext;
        $folder = 'services';

        Storage::disk('public')->makeDirectory($folder);
        $file->storeAs($folder, $name, 'public');

        $srcPath = Storage::disk('public')->path($folder . '/' . $name);
        $base    = pathinfo($name, PATHINFO_FILENAME);

        $this->generateResize($srcPath, $folder . '/' . $base . '_thumb.jpg', 300, 300, true);
        $this->generateResize($srcPath, $folder . '/' . $base . '_medium.jpg', 800, 600, false);

        return $folder . '/' . $name;
    }

    private function generateResize(string $srcPath, string $destRel, int $w, int $h, bool $cover): void
    {
        try {
            $manager = new ImageManager(new Driver());
            $img     = $manager->read($srcPath);
            $cover ? $img->cover($w, $h) : $img->scaleDown($w, $h);
            $img->save(Storage::disk('public')->path($destRel));
        } catch (\Throwable) {
            // GD unavailable — skip silently
        }
    }

    private function deleteServiceImage(string $path): void
    {
        $base = pathinfo($path, PATHINFO_FILENAME);
        $dir  = pathinfo($path, PATHINFO_DIRNAME);
        Storage::disk('public')->delete($path);
        Storage::disk('public')->delete($dir . '/' . $base . '_thumb.jpg');
        Storage::disk('public')->delete($dir . '/' . $base . '_medium.jpg');
    }
}
