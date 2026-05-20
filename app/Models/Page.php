<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title', 'content', 'hero_heading', 'hero_lede', 'services_lede', 'slug', 'priority',
        'meta_title', 'meta_description', 'published', 'hero_image',
        'why_eyebrow', 'why_heading', 'why_image', 'why_caption',
        'why_metric1_value', 'why_metric1_label', 'why_metric2_value', 'why_metric2_label',
        'why_item1_title', 'why_item1_desc', 'why_item2_title', 'why_item2_desc',
        'why_item3_title', 'why_item3_desc', 'why_item4_title', 'why_item4_desc',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public array $translatable = [
        'title', 'content', 'hero_heading', 'hero_lede', 'services_lede', 'meta_title', 'meta_description',
        'why_eyebrow', 'why_heading', 'why_caption',
        'why_metric1_label', 'why_metric2_label',
        'why_item1_title', 'why_item1_desc', 'why_item2_title', 'why_item2_desc',
        'why_item3_title', 'why_item3_desc', 'why_item4_title', 'why_item4_desc',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function pageSettings()
    {
        return $this->hasMany(PageSetting::class);
    }

    public function getSetting(string $code, $default = null)
    {
        if ($this->relationLoaded('pageSettings')) {
            return $this->pageSettings->keyBy('code')[$code]->content ?? $default;
        }

        return $this->pageSettings()
            ->where('code', $code)
            ->value('content') ?? $default;
    }

    public function getSettingsAttribute()
    {
        $map = [];
        foreach ($this->pageSettings as $s) {
            $map[$s->code] = $s->content;
        }
        return new Fluent($map);
    }

    public function getAttributeValByLanguage($attr, $language)
    {
        $obj = json_decode($this->$attr);
        return $obj->$language ?? '';
    }

    public function getValueByLanguage($jsonObj, $language)
    {
        $obj = json_decode($jsonObj, true);
        return $obj[$language] ?? (is_array($obj) ? reset($obj) : '');
    }

    public function getValueByFirstLanguage($jsonObj)
    {
        $obj = json_decode($jsonObj, true);
        if (!is_array($obj)) return '';
        $firstLanguage = Language::active()->first()->slug ?? Language::first()->slug ?? 'fr';
        return $obj[$firstLanguage] ?? reset($obj) ?? '';
    }
}
