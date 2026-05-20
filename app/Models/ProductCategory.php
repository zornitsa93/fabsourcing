<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class ProductCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'slug', 'slug_en', 'name', 'description', 'long_description',
        'icon', 'image', 'sort_order', 'published', 'featured', 'featured_order',
    ];

    public array $translatable = ['name', 'description', 'long_description'];

    protected $casts = [
        'published'      => 'boolean',
        'featured'       => 'boolean',
        'featured_order' => 'integer',
        'sort_order'     => 'integer',
    ];

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('published', true)
                     ->where('featured', true)
                     ->orderBy('featured_order');
    }

    public function scopeForCatalog(Builder $query): Builder
    {
        return $query->where('published', true)
                     ->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        $base  = pathinfo($this->image, PATHINFO_FILENAME);
        $dir   = pathinfo($this->image, PATHINFO_DIRNAME);
        $thumb = $dir . '/' . $base . '_thumb.jpg';
        return Storage::disk('public')->exists($thumb)
            ? Storage::disk('public')->url($thumb)
            : Storage::disk('public')->url($this->image);
    }

    public function getMediumUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        $base   = pathinfo($this->image, PATHINFO_FILENAME);
        $dir    = pathinfo($this->image, PATHINFO_DIRNAME);
        $medium = $dir . '/' . $base . '_medium.jpg';
        return Storage::disk('public')->exists($medium)
            ? Storage::disk('public')->url($medium)
            : Storage::disk('public')->url($this->image);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
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
        $routeName = $lang === 'en' ? 'products.category.en' : 'products.category';
        return route($routeName, [
            'lang'         => $lang,
            'categorySlug' => $this->getSlugForLang($lang),
        ]);
    }

    protected static function booted(): void
    {
        static::deleting(function (ProductCategory $cat) {
            if ($cat->image) {
                $base = pathinfo($cat->image, PATHINFO_FILENAME);
                $dir  = pathinfo($cat->image, PATHINFO_DIRNAME);
                Storage::disk('public')->delete($cat->image);
                Storage::disk('public')->delete($dir . '/' . $base . '_thumb.jpg');
                Storage::disk('public')->delete($dir . '/' . $base . '_medium.jpg');
            }
        });
    }
}
