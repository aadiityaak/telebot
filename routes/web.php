<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\BlockedIpController;
use App\Http\Controllers\Settings\TelegramSettingController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('logs', [TelegramController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('logs');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/blocked-ips', [BlockedIpController::class, 'index'])->name('blocked-ips.index');
    Route::post('/blocked-ips', [BlockedIpController::class, 'store'])->name('blocked-ips.store');
    Route::put('/blocked-ips/{blockedIp}', [BlockedIpController::class, 'update'])->name('blocked-ips.update');
    Route::delete('/blocked-ips/{blockedIp}', [BlockedIpController::class, 'destroy'])->name('blocked-ips.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('settings')->group(function () {
    Route::get('/telegram', [TelegramSettingController::class, 'edit'])->name('settings.telegram');
    Route::post('/telegram', [TelegramSettingController::class, 'update'])->name('settings.telegram.update');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
