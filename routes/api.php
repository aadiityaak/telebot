<?php

use App\Http\Middleware\LogRequestMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;

Route::middleware([LogRequestMiddleware::class])->group(function () {
  Route::post('/telegram/send', [TelegramController::class, 'send']);
  // Route::get('/telegram/log', [TelegramController::class, 'log']);
  // Tambahkan route lain yang ingin dicatat log-nya di sini
});
