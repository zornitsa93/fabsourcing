<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductsController extends Controller
{
    public function index(Request $request): View
    {
        $search     = $request->input('search', '');
        $categoryId = $request->input('category_id', '');

        $categories = ProductCategory::orderBy('sort_order')->get();

        $products = Product::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn($q) => $q->where('product_category_id', $categoryId))
            ->orderBy('product_category_id')
            ->orderBy('sort_order')
            ->paginate(40)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    public function create(): View
    {
        $categories = ProductCategory::orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_category_id'  => 'required|exists:product_categories,id',
            'slug'                 => 'required|string|max:120|unique:products,slug',
            'name.fr'              => 'required|string|max:255',
            'name.en'              => 'nullable|string|max:255',
            'short_description.fr' => 'nullable|string',
            'short_description.en' => 'nullable|string',
            'full_description.fr'  => 'nullable|string',
            'full_description.en'  => 'nullable|string',
            'materials.fr'         => 'nullable|string',
            'materials.en'         => 'nullable|string',
            'specifications.fr'    => 'nullable|string',
            'specifications.en'    => 'nullable|string',
            'meta_title.fr'        => 'nullable|string|max:255',
            'meta_title.en'        => 'nullable|string|max:255',
            'meta_description.fr'  => 'nullable|string|max:500',
            'meta_description.en'  => 'nullable|string|max:500',
            'sort_order'           => 'nullable|integer|min:0',
            'main_image'           => 'nullable|image|max:4096',
            'gallery_images.*'     => 'nullable|image|max:4096',
        ]);

        $product = new Product();
        $product->product_category_id = $request->input('product_category_id');
        $product->slug       = $request->input('slug');
        $product->sort_order = $request->input('sort_order', 0);
        $product->published  = $request->boolean('published');

        $this->fillTranslatable($product, $request);

        if ($request->hasFile('main_image')) {
            $product->main_image = $this->storeProductImage($request->file('main_image'), 'products/main');
        }

        $product->save();

        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $this->storeProductImage($file, 'products/gallery');
            }
            $product->gallery_images = $gallery;
            $product->save();
        }

        if ($request->input('action') === 'continue') {
            return redirect()->route('products.edit', $product)
                ->with('success', 'Продуктът е създаден. Продължете редактирането.');
        }

        return redirect()->route('products.index')
            ->with('success', 'Продукт „' . $product->getTranslation('name', 'fr', false) . '" е създаден.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'product_category_id'  => 'required|exists:product_categories,id',
            'slug'                 => 'required|string|max:120|unique:products,slug,' . $product->id,
            'name.fr'              => 'required|string|max:255',
            'name.en'              => 'nullable|string|max:255',
            'short_description.fr' => 'nullable|string',
            'short_description.en' => 'nullable|string',
            'full_description.fr'  => 'nullable|string',
            'full_description.en'  => 'nullable|string',
            'materials.fr'         => 'nullable|string',
            'materials.en'         => 'nullable|string',
            'specifications.fr'    => 'nullable|string',
            'specifications.en'    => 'nullable|string',
            'meta_title.fr'        => 'nullable|string|max:255',
            'meta_title.en'        => 'nullable|string|max:255',
            'meta_description.fr'  => 'nullable|string|max:500',
            'meta_description.en'  => 'nullable|string|max:500',
            'sort_order'           => 'nullable|integer|min:0',
            'main_image'           => 'nullable|image|max:4096',
            'gallery_images.*'     => 'nullable|image|max:4096',
        ]);

        $product->product_category_id = $request->input('product_category_id');
        $product->slug       = $request->input('slug');
        $product->sort_order = $request->input('sort_order', 0);
        $product->published  = $request->boolean('published');

        $this->fillTranslatable($product, $request);

        if ($request->boolean('remove_main_image') && $product->main_image) {
            $this->deleteImageWithVariants($product->main_image);
            $product->main_image = null;
        } elseif ($request->hasFile('main_image')) {
            if ($product->main_image) $this->deleteImageWithVariants($product->main_image);
            $product->main_image = $this->storeProductImage($request->file('main_image'), 'products/main');
        }

        // Preserve gallery items the user didn't remove
        $keep     = $request->input('keep_gallery', []);
        $existing = $product->gallery_images ?? [];
        foreach ($existing as $path) {
            if (!in_array($path, $keep)) {
                $this->deleteImageWithVariants($path);
            }
        }
        $gallery = array_values(array_intersect($existing, $keep));

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $gallery[] = $this->storeProductImage($file, 'products/gallery');
            }
        }
        $product->gallery_images = $gallery ?: null;

        $product->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('products.edit', $product)
                ->with('success', 'Записано.');
        }

        return redirect()->route('products.index')
            ->with('success', 'Продуктът е обновен.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->main_image) $this->deleteImageWithVariants($product->main_image);
        foreach ($product->gallery_images ?? [] as $path) {
            $this->deleteImageWithVariants($path);
        }

        $name = $product->getTranslation('name', 'fr', false);
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Продукт „' . $name . '" е изтрит.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        foreach ($request->input('order') as $i => $id) {
            Product::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['ok' => true]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function fillTranslatable(Product $product, Request $request): void
    {
        $fields = [
            'name', 'short_description', 'full_description',
            'materials', 'specifications', 'meta_title', 'meta_description',
        ];
        foreach ($fields as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $product->setTranslation($field, $locale, $value);
                }
            }
        }

        // Features submitted as features_fr[] / features_en[]
        foreach (['fr', 'en'] as $locale) {
            $bullets = array_values(
                array_filter($request->input("features_{$locale}", []), fn($b) => trim($b) !== '')
            );
            $product->setTranslation('features', $locale, $bullets);
        }
    }

    private function storeProductImage(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        $ext  = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
        $name = uniqid('img_', true) . '.' . $ext;

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
            // GD may not be available; skip silently
        }
    }

    private function deleteImageWithVariants(string $path): void
    {
        Storage::disk('public')->delete($path);
        $base = pathinfo($path, PATHINFO_FILENAME);
        $dir  = pathinfo($path, PATHINFO_DIRNAME);
        Storage::disk('public')->delete($dir . '/' . $base . '_thumb.jpg');
        Storage::disk('public')->delete($dir . '/' . $base . '_medium.jpg');
    }
}
