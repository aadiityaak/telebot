<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'method',
        'endpoint',
        'payload',
        'headers',
        'status_code',
        'response_body',
        'duration_ms',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'response_body' => 'array',
    ];
}
