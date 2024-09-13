<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display setting page.
     */
    public function displaySetting()
    {
        $setting = Setting::first() ?? new Setting();
        return view('backend.dashboard.settings', compact('setting'));
    }

    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        return response()->json($setting);
    }

    public function store(Request $request)
    {
        // return response()->json(['from' => 'store',$request->all()]);
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_phone' => 'required|string|regex:/^\+628[0-9]{8,11}$/',
            'logo_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'member_card_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'member_discount' => 'sometimes|nullable|numeric|min:0|max:100',
        ]);

        $setting = Setting::first() ?? new Setting();

        // Handle logo_path
        if ($request->hasFile('logo_path')) {
            if ($setting->logo_path) {
                Storage::delete($setting->logo_path);
            }
            $validated['logo_path'] = $request->file('logo_path')->store('public/settings');
        }

        // Handle member_card_path
        if ($request->hasFile('member_card_path')) {
            if ($setting->member_card_path) {
                Storage::delete($setting->member_card_path);
            }
            $validated['member_card_path'] = $request->file('member_card_path')->store('public/settings');
        }

        $setting->fill($validated);
        $setting->save();

        return response()->json(['message' => 'Setting saved successfully', 'setting' => $setting]);
    }

    public function update(Request $request, Setting $setting)
    {
        return $this->store($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }



    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
