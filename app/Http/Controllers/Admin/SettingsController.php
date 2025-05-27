<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [];
        $settings = Setting::all();
        
        // Build validation rules based on setting types
        foreach ($settings as $setting) {
            switch ($setting->type) {
                case 'email':
                    $rules[$setting->key] = 'required|email';
                    break;
                case 'number':
                    $rules[$setting->key] = 'required|numeric|min:1';
                    break;
                case 'boolean':
                    $rules[$setting->key] = 'nullable|boolean';
                    break;
                case 'color':
                    $rules[$setting->key] = 'required|regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i';
                    break;
                default:
                    $rules[$setting->key] = 'required|string';
            }
        }

        $request->validate($rules);

        foreach ($settings as $setting) {
            $value = $request->input($setting->key);
            
            // Handle boolean values
            if ($setting->type === 'boolean') {
                $value = $request->has($setting->key) ? '1' : '0';
            }
            
            Setting::set($setting->key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cài đặt đã được cập nhật thành công!');
    }

    public function reset()
    {
        // Reset to default values by running seeder
        \Artisan::call('db:seed', ['--class' => 'SettingsSeeder']);
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Cài đặt đã được đặt lại về mặc định!');
    }

    public function export()
    {
        $settings = Setting::all()->toArray();
        
        $filename = 'settings_backup_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename=' . $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json'
        ]);

        $file = $request->file('settings_file');
        $content = file_get_contents($file->path());
        $settings = json_decode($content, true);

        if (!$settings) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'File không đúng định dạng!');
        }

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cài đặt đã được import thành công!');
    }
}
