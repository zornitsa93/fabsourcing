<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Media extends Model
{
    use HasTranslations;

    protected $table = 'media';

    protected $fillable = [
        'filename', 'original_name', 'path',
        'mime_type', 'size_bytes', 'width', 'height', 'alt_text',
    ];

    public array $translatable = ['alt_text'];

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getThumbUrlAttribute(): string
    {
        $base = pathinfo($this->path, PATHINFO_FILENAME);
        $dir  = pathinfo($this->path, PATHINFO_DIRNAME);
        $ext  = pathinfo($this->path, PATHINFO_EXTENSION);
        $thumb = $dir . '/' . $base . '_thumb.' . $ext;
        return Storage::disk('public')->exists($thumb)
            ? Storage::url($thumb)
            : $this->url;
    }

    public function getSizeFormattedAttribute(): string
    {
        $b = $this->size_bytes ?? 0;
        if ($b >= 1048576) return round($b / 1048576, 1) . ' MB';
        if ($b >= 1024)    return round($b / 1024, 1) . ' KB';
        return $b . ' B';
    }

    /** Scan other tables to find where this image path is referenced. */
    public function findUsages(): array
    {
        $path   = $this->path;
        $usages = [];

        foreach (Page::where('hero_image', $path)->get() as $p) {
            $usages[] = ['type' => 'Страница', 'label' => $p->slug, 'edit' => route('pages.edit', $p)];
        }
        foreach (Product::where('main_image', $path)->get() as $p) {
            $usages[] = ['type' => 'Продукт (главна)', 'label' => $p->getTranslation('name', 'fr', false) ?: $p->slug, 'edit' => route('products.edit', $p)];
        }
        foreach (Product::whereJsonContains('gallery_images', $path)->get() as $p) {
            $usages[] = ['type' => 'Продукт (галерия)', 'label' => $p->getTranslation('name', 'fr', false) ?: $p->slug, 'edit' => route('products.edit', $p)];
        }
        foreach (BlogPost::where('featured_image', $path)->get() as $p) {
            $usages[] = ['type' => 'Блог пост (снимка)', 'label' => $p->getTranslation('title', 'fr', false) ?: $p->slug, 'edit' => route('blog-posts.edit', $p)];
        }

        return $usages;
    }
}
