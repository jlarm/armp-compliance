<?php

declare(strict_types=1);

use App\Http\Controllers\Central\DealershipController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dealerships', [DealershipController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dealerships.index');

Route::get('dealerships/create', [DealershipController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dealerships.create');

Route::post('/dealerships', [DealershipController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('dealerships.store');

require __DIR__.'/settings.php';
