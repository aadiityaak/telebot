<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TelegramController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('logs', [TelegramController::class, 'logRequest'])
    ->middleware(['auth', 'verified'])
    ->name('logs');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
