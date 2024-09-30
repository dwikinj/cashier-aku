<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display setting page.
     */
    public function showAdminSetting()
    {
        $setting = Setting::first();
        return view('backend.dashboard.settings', compact('setting'));
    }

    public function updateAdminSetting(Request $request)
    {
        try {
            // Validasi input dengan pesan error kustom
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|min:3|max:255',
                'company_address' => 'required|string|min:3|max:255',
                'company_phone' => 'required|string|regex:/^\+628[0-9]{8,11}$/',
                'logo_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
                'member_card_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
                'member_discount' => 'sometimes|nullable|numeric|min:0|max:100',
            ], [
                'company_name.required' => 'Nama perusahaan harus diisi.',
                'company_name.min' => 'Nama perusahaan minimal 3 karakter.',
                'company_name.max' => 'Nama perusahaan maksimal 255 karakter.',
                'company_address.required' => 'Alamat perusahaan harus diisi.',
                'company_address.min' => 'Alamat perusahaan minimal 3 karakter.',
                'company_address.max' => 'Alamat perusahaan maksimal 255 karakter.',
                'company_phone.required' => 'Nomor telepon perusahaan harus diisi.',
                'company_phone.regex' => 'Format nomor telepon tidak valid. Gunakan format: +628xxxxxxxxxx.',
                'logo_path.image' => 'File logo harus berupa gambar.',
                'logo_path.mimes' => 'Format logo yang diizinkan: jpeg, png, jpg.',
                'logo_path.max' => 'Ukuran logo maksimal 2MB.',
                'member_card_path.image' => 'File kartu anggota harus berupa gambar.',
                'member_card_path.mimes' => 'Format kartu anggota yang diizinkan: jpeg, png, jpg.',
                'member_card_path.max' => 'Ukuran kartu anggota maksimal 2MB.',
                'member_discount.numeric' => 'Diskon anggota harus berupa angka.',
                'member_discount.min' => 'Diskon anggota minimal 0%.',
                'member_discount.max' => 'Diskon anggota maksimal 100%.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            $setting = Setting::first();

            // Handle logo_path
            if ($request->hasFile('logo_path')) {
                if ($setting->logo_path) {
                    Storage::delete('public/' . str_replace('storage/', '', $setting->logo_path));
                }
                $path = $request->file('logo_path')->store('public/settings');
                $validated['logo_path'] = 'storage/' . str_replace('public/', '', $path);
            }

            // Handle member_card_path
            if ($request->hasFile('member_card_path')) {
                if ($setting->member_card_path) {
                    Storage::delete('public/' . str_replace('storage/', '', $setting->member_card_path));
                }
                $path = $request->file('member_card_path')->store('public/settings');
                $validated['member_card_path'] = 'storage/' . str_replace('public/', '', $path);
            }

            // Update setting
            $setting->fill($validated);
            $setting->save();

            return response()->json(['message' => 'Setting saved successfully', 'setting' => $setting]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update setting',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
