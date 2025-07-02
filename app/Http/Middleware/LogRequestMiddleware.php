<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RequestLog;
use App\Models\BlockedIp;
use App\Models\Setting;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $start = microtime(true);

        // ✅ Cek IP terblokir
        if (BlockedIp::where('ip_address', $ip)->where('is_active', true)->exists()) {
            $duration = (int) ((microtime(true) - $start) * 1000);
            $status = 403;
            $body = ['message' => 'Your IP address is blocked.'];

            RequestLog::create([
                'ip_address'    => $ip,
                'user_agent'    => $request->userAgent(),
                'method'        => $request->method(),
                'endpoint'      => $request->path(),
                'payload'       => $request->all(),
                'headers'       => $request->headers->all(),
                'status_code'   => $status,
                'response_body' => $body,
                'duration_ms'   => $duration,
            ]);

            return response()->json($body, $status);
        }

        // ✅ Validasi API Key via query param
        $clientKey = $request->query('api_key');
        $validKey = Setting::getValue('app_api_key');

        if (!$clientKey || $clientKey !== $validKey) {
            $duration = (int) ((microtime(true) - $start) * 1000);
            $status = 401;
            $body = ['message' => 'Unauthorized - Invalid API Key'];

            RequestLog::create([
                'ip_address'    => $ip,
                'user_agent'    => $request->userAgent(),
                'method'        => $request->method(),
                'endpoint'      => $request->path(),
                'payload'       => $request->all(),
                'headers'       => $request->headers->all(),
                'status_code'   => $status,
                'response_body' => $body,
                'duration_ms'   => $duration,
            ]);

            return response()->json($body, $status);
        }

        // ✅ Lanjutkan request jika lolos semua validasi
        $response = $next($request);

        $duration = (int) ((microtime(true) - $start) * 1000);

        RequestLog::create([
            'ip_address'    => $ip,
            'user_agent'    => $request->userAgent(),
            'method'        => $request->method(),
            'endpoint'      => $request->path(),
            'payload'       => $request->all(),
            'headers'       => $request->headers->all(),
            'status_code'   => $response->getStatusCode(),
            'response_body' => json_decode($response->getContent(), true),
            'duration_ms'   => $duration,
        ]);

        return $response;
    }
}
