<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductCategoriesController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::orderBy('sort_order')->get();
        return view('admin.product-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.product-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'slug'                    => 'required|string|max:120|unique:product_categories,slug',
            'name.fr'                 => 'required|string|max:255',
            'name.en'                 => 'nullable|string|max:255',
            'description.fr'          => 'nullable|string|max:500',
            'description.en'          => 'nullable|string|max:500',
            'long_description.fr'     => 'nullable|string',
            'long_description.en'     => 'nullable|string',
            'icon'                    => 'nullable|string|max:100',
            'sort_order'              => 'nullable|integer|min:0',
            'featured_order'          => 'nullable|integer|min:1',
            'image'                   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $cat = new ProductCategory();
        $cat->slug           = $request->input('slug');
        $cat->icon           = $request->input('icon');
        $cat->sort_order     = $request->integer('sort_order', 0);
        $cat->published      = $request->boolean('published');
        $cat->featured       = $request->boolean('featured');
        $cat->featured_order = $request->boolean('featured') ? $request->integer('featured_order') : null;

        foreach (['name', 'description', 'long_description'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $v = $request->input("{$field}.{$locale}");
                if ($v !== null && $v !== '') {
                    $cat->setTranslation($field, $locale, $v);
                }
            }
        }

        if ($request->hasFile('image')) {
            $cat->image = $this->storeCategoryImage($request->file('image'), $cat->slug);
        }

        $cat->save();

        $this->warnIfTooManyFeatured($request);

        if ($request->input('action') === 'continue') {
            return redirect()->route('product-categories.edit', $cat)
                ->with('success', 'Категорията е създадена. Продължете редактирането.');
        }

        return redirect()->route('product-categories.index')
            ->with('success', 'Категория „' . $cat->getTranslation('name', 'fr', false) . '" е създадена.');
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('admin.product-categories.edit', ['category' => $productCategory]);
    }

    public function update(Request $request, ProductCategory $productCategory): RedirectResponse
    {
        $request->validate([
            'slug'                    => 'required|string|max:120|unique:product_categories,slug,' . $productCategory->id,
            'name.fr'                 => 'required|string|max:255',
            'name.en'                 => 'nullable|string|max:255',
            'description.fr'          => 'nullable|string|max:500',
            'description.en'          => 'nullable|string|max:500',
            'long_description.fr'     => 'nullable|string',
            'long_description.en'     => 'nullable|string',
            'icon'                    => 'nullable|string|max:100',
            'sort_order'              => 'nullable|integer|min:0',
            'featured_order'          => 'nullable|integer|min:1',
            'image'                   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $productCategory->slug           = $request->input('slug');
        $productCategory->icon           = $request->input('icon');
        $productCategory->sort_order     = $request->integer('sort_order', 0);
        $productCategory->published      = $request->boolean('published');
        $productCategory->featured       = $request->boolean('featured');
        $productCategory->featured_order = $request->boolean('featured') ? $request->integer('featured_order') : null;

        foreach (['name', 'description', 'long_description'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $v = $request->input("{$field}.{$locale}");
                if ($v !== null) {
                    $productCategory->setTranslation($field, $locale, $v);
                }
            }
        }

        if ($request->boolean('remove_image') && $productCategory->image) {
            $this->deleteCategoryImage($productCategory->image);
            $productCategory->image = null;
        } elseif ($request->hasFile('image')) {
            if ($productCategory->image) {
                $this->deleteCategoryImage($productCategory->image);
            }
            $productCategory->image = $this->storeCategoryImage($request->file('image'), $productCategory->slug);
        }

        $productCategory->save();

        $this->warnIfTooManyFeatured($request);

        if ($request->input('action') === 'continue') {
            return redirect()->route('product-categories.edit', $productCategory)
                ->with('success', 'Записано.');
        }

        return redirect()->route('product-categories.index')
            ->with('success', 'Категорията е обновена.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'Не може да изтриете категория с продукти. Първо преместете или изтрийте продуктите.');
        }

        $name = $productCategory->getTranslation('name', 'fr', false);
        $productCategory->delete(); // booted() handles image deletion

        return redirect()->route('product-categories.index')
            ->with('success', 'Категория „' . $name . '" е изтрита.');
    }

    private function warnIfTooManyFeatured(Request $request): void
    {
        $count = ProductCategory::where('featured', true)->count();
        if ($count > 5) {
            session()->flash('warning', 'Имате ' . $count . ' акцентирани категории. Началната страница показва само 5 (по реда на „Ред на началната страница"). Препоръчително е да оставите точно 5.');
        }
    }

    private function storeCategoryImage(\Illuminate\Http\UploadedFile $file, string $slug): string
    {
        $ext    = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $name   = Str::slug($slug) . '-' . time() . '.' . $ext;
        $folder = 'categories';

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

    private function deleteCategoryImage(string $path): void
    {
        $base = pathinfo($path, PATHINFO_FILENAME);
        $dir  = pathinfo($path, PATHINFO_DIRNAME);
        Storage::disk('public')->delete($path);
        Storage::disk('public')->delete($dir . '/' . $base . '_thumb.jpg');
        Storage::disk('public')->delete($dir . '/' . $base . '_medium.jpg');
    }
}
