<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
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
        return pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME) . '_og.jpg';
    }

    /**
     * 1200x630 JPEG copy of the featured image for og:image / twitter:image.
     * Created on first use; falls back to the original if it cannot be made.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) return null;

        $disk = Storage::disk('public');
        $og   = self::ogPathFor($this->featured_image);

        if (!$disk->exists($og) && $disk->exists($this->featured_image)) {
            try {
                (new ImageManager(new Driver()))
                    ->read($disk->path($this->featured_image))
                    ->cover(1200, 630)
                    ->toJpeg(82)
                    ->save($disk->path($og));
            } catch (\Throwable) {
                // GD unavailable or unreadable file: use the original
            }
        }

        return Storage::url($disk->exists($og) ? $og : $this->featured_image);
    }
}
