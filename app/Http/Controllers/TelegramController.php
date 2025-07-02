<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\RequestLog;
use App\Models\Setting;


use Inertia\Inertia;

class TelegramController extends Controller
{
    protected string $botToken;

    public function __construct()
    {
        $this->botToken = Setting::where('key', 'telegram_token')->value('value');
    }

    /**
     * Kirim pesan ke Telegram.
     */
    public function send(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|string',
            'text'    => 'required|string',
            'parse_mode' => 'nullable|string',
        ]);

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        $response = Http::post($url, [
            'chat_id' => $request->input('chat_id'),
            'text'    => $request->input('text'),
            'parse_mode' => $request->input('parse_mode') ?? 'HTML',
        ]);

        return response()->json([
            'success' => $response->successful(),
            'data'    => $response->json(),
        ], $response->status());
    }

    /**
     * Endpoint untuk melihat log terakhir (sementara).
     */
    public function index(Request $request)
    {
        $allowedSorts = ['id', 'ip_address', 'method', 'endpoint', 'status_code', 'duration_ms', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $order = in_array($request->get('order'), ['asc', 'desc']) ? $request->get('order') : 'desc';

        $logs = RequestLog::orderBy($sort, $order)
            ->paginate(10)
            ->appends($request->all());

        return Inertia::render('RequestLog/Index', [
            'logs' => $logs,
            'sort' => $sort,
            'order' => $order,
        ]);
    }
}
