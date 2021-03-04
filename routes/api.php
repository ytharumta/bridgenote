<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {
    Route::get('all', [App\Http\Controllers\AuthController::class, 'all']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('edit/{id}', [App\Http\Controllers\AuthController::class, 'edit']);
    Route::get('delete/{id}', [App\Http\Controllers\AuthController::class, 'delete']);
    Route::get('show/{id}', [App\Http\Controllers\AuthController::class, 'show']);
});
