<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class BlogPost extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'featured_image',
        'author_name', 'published_at', 'tags',
        'reading_time_minutes', 'meta_title', 'meta_description',
    ];

    public array $translatable = [
        'title', 'excerpt', 'body', 'tags', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

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
}
