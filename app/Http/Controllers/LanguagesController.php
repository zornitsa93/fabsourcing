<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use App\Models\PageSetting;
use App\Models\SettingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('id')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|alpha|max:10|unique:languages,slug',
            'name' => 'required|max:100',
        ]);

        $slug = strtolower($request->slug);

        Language::create([
            'slug'   => $slug,
            'name'   => $request->name,
            'active' => true,
        ]);

        $this->propagateAddLanguage($slug);
        Cache::forget('languages');

        return redirect()->route('languages.index')
            ->with('success', 'Езикът е добавен успешно.');
    }

    public function toggle(Language $language)
    {
        $language->update(['active' => !$language->active]);
        Cache::forget('languages');

        return redirect()->route('languages.index')
            ->with('success', 'Статусът на езика е актуализиран.');
    }

    public function destroy(Language $language)
    {
        $this->propagateRemoveLanguage($language->slug);
        $language->delete();
        Cache::forget('languages');

        return redirect()->route('languages.index')
            ->with('success', 'Езикът е изтрит успешно.');
    }

    private function langSettingTypeIds(): \Illuminate\Support\Collection
    {
        return SettingType::whereIn('name', ['string', 'textarea'])->pluck('id');
    }

    private function propagateAddLanguage(string $slug): void
    {
        foreach (Page::all() as $page) {
            $changed = false;
            foreach (['title', 'content'] as $col) {
                $data = json_decode($page->$col, true) ?? [];
                if (!array_key_exists($slug, $data)) {
                    $data[$slug] = '';
                    $page->$col = json_encode($data, JSON_UNESCAPED_UNICODE);
                    $changed = true;
                }
            }
            if ($changed) $page->save();
        }

        $typeIds = $this->langSettingTypeIds();
        foreach (PageSetting::whereIn('setting_type_id', $typeIds)->get() as $setting) {
            $data = json_decode($setting->content, true) ?? [];
            if (!array_key_exists($slug, $data)) {
                $data[$slug] = '';
                $setting->content = json_encode($data, JSON_UNESCAPED_UNICODE);
                $setting->save();
            }
        }
    }

    private function propagateRemoveLanguage(string $slug): void
    {
        foreach (Page::all() as $page) {
            $changed = false;
            foreach (['title', 'content'] as $col) {
                $data = json_decode($page->$col, true) ?? [];
                if (array_key_exists($slug, $data)) {
                    unset($data[$slug]);
                    $page->$col = json_encode($data, JSON_UNESCAPED_UNICODE);
                    $changed = true;
                }
            }
            if ($changed) $page->save();
        }

        $typeIds = $this->langSettingTypeIds();
        foreach (PageSetting::whereIn('setting_type_id', $typeIds)->get() as $setting) {
            $data = json_decode($setting->content, true) ?? [];
            if (array_key_exists($slug, $data)) {
                unset($data[$slug]);
                $setting->content = json_encode($data, JSON_UNESCAPED_UNICODE);
                $setting->save();
            }
        }
    }
}
