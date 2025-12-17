<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PeselController;

Route::get('/', [PeselController::class, 'index'])->name('main-page');
Route::middleware('throttle:25,1')->post('/generator', [PeselController::class, 'generator'])->name('generator');