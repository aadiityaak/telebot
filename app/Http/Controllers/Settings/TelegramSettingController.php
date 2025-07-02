<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;

class TelegramSettingController extends Controller
{
    public function edit()
    {
        $token = Setting::getValue('telegram_token', '');

        return Inertia::render('settings/Telegram', [
            'token' => $token,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bot_token' => ['required', 'string'],
        ]);

        Setting::setValue('telegram_token', $validated['bot_token']);

        return back()->with('success', 'Token Telegram berhasil disimpan.');
    }
}
