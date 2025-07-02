<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RequestLog;

class indexMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = (int) ((microtime(true) - $start) * 1000);

        RequestLog::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
            'status_code' => $response->getStatusCode(),
            'response_body' => json_decode($response->getContent(), true),
            'duration_ms' => $duration,
        ]);

        return $response;
    }
}
