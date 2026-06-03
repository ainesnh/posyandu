<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:50',
            'title_banner_home' => 'required|string',
            'title_banner_menu' => 'required|string',
            'banner_home' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner_menu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        Setting::updateValue('app_name', $request->app_name);
        Setting::updateValue('title_banner_home', $request->title_banner_home);
        Setting::updateValue('title_banner_menu', $request->title_banner_menu);

        if ($request->hasFile('banner_home')) {
            $this->uploadImage($request->file('banner_home'), 'banner_home_path');
        }

        if ($request->hasFile('banner_menu')) {
            $this->uploadImage($request->file('banner_menu'), 'banner_menu_path');
        }

        return back()->with('success', 'Pengaturan website berhasil diperbarui!');
    }

    private function uploadImage($file, $settingKey)
    {
        $destinationPath = public_path('uploads/settings');
        
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }

        $oldFile = Setting::getValue($settingKey);
        if ($oldFile && File::exists($destinationPath . '/' . $oldFile) && $oldFile != 'default-home.jpg' && $oldFile != 'default-menu.jpg') {
            File::delete($destinationPath . '/' . $oldFile);
        }

        $fileName = $settingKey . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $fileName);

        Setting::updateValue($settingKey, $fileName);
    }
}