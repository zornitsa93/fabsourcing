<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title', 'description', 'long_description',
        'icon', 'number', 'slug',
        'image', 'col_span', 'featured', 'published', 'sort_order',
    ];

    protected $casts = [
        'featured'  => 'boolean',
        'published' => 'boolean',
    ];

    public array $translatable = ['title', 'description', 'long_description'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        $base = pathinfo($this->image, PATHINFO_FILENAME);
        $dir  = pathinfo($this->image, PATHINFO_DIRNAME);
        $thumb = $dir . '/' . $base . '_thumb.jpg';
        return Storage::disk('public')->exists($thumb)
            ? Storage::disk('public')->url($thumb)
            : Storage::disk('public')->url($this->image);
    }

    protected static function booted(): void
    {
        static::deleting(function (Service $service) {
            if ($service->image) {
                $base = pathinfo($service->image, PATHINFO_FILENAME);
                $dir  = pathinfo($service->image, PATHINFO_DIRNAME);
                Storage::disk('public')->delete($service->image);
                Storage::disk('public')->delete($dir . '/' . $base . '_thumb.jpg');
                Storage::disk('public')->delete($dir . '/' . $base . '_medium.jpg');
            }
        });
    }
}
