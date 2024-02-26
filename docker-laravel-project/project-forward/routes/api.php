<?php

namespace App\Http\Controllers;

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

Route::get('/validate', [InputController::class, 'validate'])->name('validate');



Route::get('/enabled', static function() {
    return response()->json([
        'message' => 'true'
    ], 200);
})->name('enable');


Route::match(['get', 'post', 'put', 'patch', 'delete'],'/', static function() {
    return response()->json([
        'message' => __('auth.unauthorized')
    ], 401);
})->name('/');

/**
 *Fallback route
 */
Route::fallback(static function () {
    return response()->json([
        'message' => __('auth.unauthorized')
    ], 401);
})->name('fallback');
