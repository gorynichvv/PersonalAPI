<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConnectionController;
use Illuminate\Support\Facades\Route;
use App\Services\UserService;

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

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(
    ['prefix' => 'v1', 'middleware' => ['auth:sanctum']],
    function () {
        Route::get('/me', [UserController::class, 'show']);
        Route::put('/me', [UserController::class, 'update']);
        Route::get('/me/connections', [ConnectionController::class, 'index']);
        Route::post('/logout', [AuthController::class, 'logout']);
        // Route::get('/test', [UserService::class, 'test']);
    }
);
