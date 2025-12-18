<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorize('view settings');
        
        return view('dashboard.settings.index');
    }

    public function update(Request $request)
    {
        $this->authorize('manage settings');
        
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_email' => 'required|email|max:255',
            'app_phone' => 'nullable|string|max:20',
            'app_address' => 'nullable|string|max:500',
            'sms_enabled' => 'required|boolean',
            'sms_sender' => 'required|string|max:20',
            'sms_username' => 'nullable|string|max:100',
            'sms_api_key' => 'nullable|string|max:255',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $settings = [
            'app_name' => $request->app_name,
            'app_email' => $request->app_email,
            'app_phone' => $request->app_phone,
            'app_address' => $request->app_address,
            'sms_enabled' => $request->sms_enabled,
            'sms_sender' => $request->sms_sender,
            'sms_username' => $request->sms_username,
            'sms_api_key' => $request->sms_api_key,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear cache
        Cache::forget('settings');

        return redirect()->route('dashboard.settings.index')
            ->with('success', t('Settings updated successfully'));
    }
}
