<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use App\Models\Language;

class PageSetting extends Model
{
    protected $fillable = ['page_id', 'code', 'content', 'setting_type_id', 'field_name'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function settingType()
    {
        return $this->belongsTo(SettingType::class);
    }

    public function deleteFile()
    {
        Storage::disk('public')->delete($this->content);
            $this->update(['content'=>'']);
        return 'success';
    }

    public function getAttributeValByLanguage($attr, $language)
    {
        $obj = json_decode($this->$attr);
        return $obj->$language;
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
        $firstLanguage = Language::active()->first()->slug ?? Language::first()->slug;
        return $obj[$firstLanguage] ?? reset($obj) ?? '';
    }
}
