<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = $this->settingsService->all();
        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $this->settingsService->set($key, $value);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully.'
            ]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
