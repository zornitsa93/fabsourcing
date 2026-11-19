<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\OgImage;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class BlogPost extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'slug', 'slug_en', 'title', 'excerpt', 'body', 'featured_image',
        'author_name', 'published_at', 'tags',
        'reading_time_minutes', 'meta_title', 'meta_description',
    ];

    public array $translatable = [
        'title', 'excerpt', 'body', 'tags', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getSlugForLang(string $lang): string
    {
        return ($lang === 'en' && $this->slug_en) ? $this->slug_en : $this->slug;
    }

    // draft | scheduled | published
    public function getStatusAttribute(): string
    {
        if (!$this->published_at) return 'draft';
        return $this->published_at->isFuture() ? 'scheduled' : 'published';
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? Storage::url($this->featured_image) : null;
    }

    /** Relative path of the 1200x630 Open Graph copy of the featured image. */
    public static function ogPathFor(string $path): string
    {
        return OgImage::pathFor($path);
    }

    /** 1200x630 copy of the featured image for og:image / twitter:image (see OgImage). */
    public function getOgImageUrlAttribute(): ?string
    {
        return OgImage::url($this->featured_image);
    }
}
