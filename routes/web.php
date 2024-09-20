<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('index');
Route::post('/subscribe', [ProductController::class, 'subscribe'])->name('subscribe');
