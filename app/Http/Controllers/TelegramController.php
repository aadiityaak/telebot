<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\RequestLog;

class TelegramController extends Controller
{
    protected string $botToken;

    public function __construct()
    {
        // Idealnya ini diambil dari .env atau config
        $this->botToken = config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
    }

    /**
     * Kirim pesan ke Telegram.
     */
    public function send(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|string',
            'text'    => 'required|string',
        ]);

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $response = Http::post($url, [
            'chat_id' => $request->input('chat_id'),
            'text'    => $request->input('text'),
        ]);

        return response()->json([
            'success' => $response->successful(),
            'data'    => $response->json(),
        ], $response->status());
    }

    /**
     * Endpoint untuk melihat log terakhir (sementara).
     */
    public function log()
    {
        return RequestLog::orderByDesc('created_at')->limit(100)->get();
    }
}
