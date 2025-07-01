<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\RequestLog;

use Inertia\Inertia;

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
    public function logRequest()
    {
        // Ambil data log dari database
        $logs = RequestLog::orderBy('created_at', 'desc')->get();

        // Pastikan mengirim array, bukan Collection
        return Inertia::render('RequestLog', [
            'data_logs' => $logs->toArray(),
        ]);
    }
}
