<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'product_category_id', 'slug', 'slug_en', 'name', 'short_description', 'full_description',
        'features', 'materials', 'specifications', 'main_image', 'gallery_images',
        'tag_number', 'sort_order', 'published', 'featured', 'meta_title', 'meta_description',
    ];

    public array $translatable = [
        'name', 'short_description', 'full_description',
        'features', 'materials', 'specifications',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'published'      => 'boolean',
        'featured'       => 'boolean',
        'gallery_images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function getMainImageUrlAttribute(): ?string
    {
        return $this->main_image ? Storage::url($this->main_image) : null;
    }

    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery_images ?? [])->map(fn($p) => Storage::url($p))->all();
    }

    public function getSlugForLang(string $lang): string
    {
        if ($lang === 'en' && $this->slug_en) {
            return $this->slug_en;
        }
        return $this->slug;
    }

    public function getUrlForLang(string $lang): string
    {
        $routeName = $lang === 'en' ? 'products.detail.en' : 'products.detail';
        return route($routeName, [
            'lang'         => $lang,
            'categorySlug' => $this->category->getSlugForLang($lang),
            'productSlug'  => $this->getSlugForLang($lang),
        ]);
    }
}
