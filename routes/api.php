<?php

use App\Http\Middleware\indexMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramController;

Route::middleware([indexMiddleware::class])->group(function () {
  Route::post('/telegram/send', [TelegramController::class, 'send']);
});
