<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PageSetting;
use App\Models\Page;
use App\Models\SettingType;
use App\Models\Language;

class PageSettingsController extends Controller
{
    public function index()
    {
        $settings = PageSetting::all();

        return view('admin.page-settings.index',compact('settings'));
    }

    public function create(Request $request)
    {
        $pageId = $request->page_id; // взимаме ако идва от линка
        $pages = Page::all();
        $types = SettingType::all();
        $languages = Language::active()->get();

        return view('admin.page-settings.create', compact('pages', 'types', 'languages', 'pageId'));
    }

    // public function create()
    // {
    //     $pages = Page::all();
    //     $types = SettingType::all();
    //     $languages = Language::all();

    //     return view('admin.page-settings.create',compact('pages', 'types', 'languages'));
    // }

    public function store(Request $request)
    {
        $pageSetting = PageSetting::create([
            'page_id' => $request->page_id,
            'field_name' => $request->field_name,
            'code' => $request->code,
            'setting_type_id' => $request->setting_type_id,
            'content' => NULL
        ]);

        return redirect()->route('pages.edit', $request->page_id)
            ->with('success','Настройката е създадена успешно');
    }

    public function show(PageSetting $pageSetting)
    {
        return view('admin.page-settings.show',compact('pageSetting'));
    }

    public function edit(PageSetting $pageSetting)
    {
        $pages = Page::all();
        $types = SettingType::all();
        $languages = Language::active()->get();

        return view('admin.page-settings.edit',compact('pageSetting','pages', 'types', 'languages'));
    }

    public function update(Request $request, PageSetting $pageSetting)
    {
        $typeName = $pageSetting->settingType->name;

        if ($typeName === 'string' || $typeName === 'textarea') {
            $content = json_encode($request->content, JSON_UNESCAPED_UNICODE);
        } else {
            $content = $request->content;
        }

        $pageSetting->update([
            'page_id'         => $request->page_id,
            'field_name'      => $request->field_name,
            'code'            => $request->code,
            'setting_type_id' => $request->setting_type_id,
            'content'         => $content,
        ]);


        return redirect()->route('page-settings.index')
            ->with('success','Type updated successfully');
    }

    public function destroy(PageSetting $pageSetting)
    {
        $pageSetting->delete();

        return redirect()->route('page-settings.index')
            ->with('success','Type '.$pageSetting->name.' deleted successfully');
    }

    public function deleteFile($settingId = null)
    {
        $pageSetting = PageSetting::where('id',$settingId)->first();
        if($pageSetting)$pageSetting->deleteFile();
        return back();
    }
}
