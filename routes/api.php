<?php

use App\Http\Controllers\CallbackController;
use App\Http\Controllers\MockController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::get('/mock', [MockController::class, 'handle'])->name('mock.handle');
Route::post('/payment', [PaymentController::class, 'process']);
Route::post('/callback', [CallbackController::class, 'update']);
