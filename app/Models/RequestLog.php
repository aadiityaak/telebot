<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestLog extends Model
{

    use HasFactory;

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
