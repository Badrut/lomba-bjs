<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SystemSettingController extends Controller
{
    public function index()
    {
        return response()->json(SystemSetting::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key_setting' => 'required|string|unique:system_settings,key_setting',
            'value_setting' => 'nullable|string',
            'tipe_value' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['updated_by_user_id'] = Auth::id();

        $setting = SystemSetting::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'tipe_aktivitas' => 'Create Setting',
            'deskripsi' => 'Membuat setting: ' . $setting->key_setting,
            'data_baru_json' => json_encode($setting->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($setting, 201);
    }

    public function show($id)
    {
        return response()->json(SystemSetting::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $setting = SystemSetting::findOrFail($id);

        $dataLama = $setting->toArray();

        $validated = $request->validate([
            'value_setting' => 'nullable|string',
            'tipe_value' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['updated_by_user_id'] = Auth::id();
        $setting->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'tipe_aktivitas' => 'Update Setting',
            'deskripsi' => 'Mengupdate setting: ' . $setting->key_setting,
            'data_lama_json' => json_encode($dataLama),
            'data_baru_json' => json_encode($setting->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json($setting);
    }

    public function destroy($id)
    {
        $setting = SystemSetting::findOrFail($id);
        $dataLama = $setting->toArray();
        $setting->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'tipe_aktivitas' => 'Delete Setting',
            'deskripsi' => 'Menghapus setting: ' . $setting->key_setting,
            'data_lama_json' => json_encode($dataLama),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json(['message' => 'Setting deleted successfully.']);
    }
}