<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Document extends Model {
    use HasTranslations;

    public array $translatable = ['title', 'description'];

    protected $fillable = [
        'title', 'description', 'file_path', 'original_filename',
        'file_size', 'mime_type', 'sort_order', 'published',
    ];

    protected $casts = ['published' => 'boolean'];

    public function scopePublished(Builder $q): Builder { return $q->where('published', true); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('sort_order')->orderByDesc('id'); }
}
