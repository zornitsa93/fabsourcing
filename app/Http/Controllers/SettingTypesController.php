<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingType;

class SettingTypesController extends Controller
{
    public function index()
    {
        $types = SettingType::all();

        return view('admin.setting-types.index',compact('types'));
    }

    public function create()
    {

        return view('admin.setting-types.create');
    }

    public function store(Request $request)
    {
        $settingType = SettingType::create([
            'name' => $request->name
        ]);

        return redirect()->route('setting-types.index')
            ->with('success','Създадена е успешно');
    }

    public function show(SettingType $settingType)
    {
        return view('admin.setting-types.show',compact('settingType'));
    }

    public function edit(SettingType $settingType)
    {

        return view('admin.setting-types.edit',compact('settingType'));
    }

    public function update(Request $request, SettingType $settingType)
    {
        $settingType->update([
            'name' => $request['name']
        ]);


        return redirect()->route('setting-types.index')
            ->with('success','Редактирана е успешно');
    }

    public function destroy(SettingType $settingType)
    {
        $settingType->delete();

        return redirect()->route('setting-types.index')
            ->with('success','Тип "'.$settingType->name.'" е изтрит успешно');
    }
    
}
