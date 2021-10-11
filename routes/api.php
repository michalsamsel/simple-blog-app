<?php

use App\Http\Controllers\AuthenticateUserController;
use App\Http\Controllers\PostController;
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

//AuthenticateUserController routes
Route::post('register', [AuthenticateUserController::class, 'register']);
Route::post('login', [AuthenticateUserController::class, 'login']);
Route::post('logout', [AuthenticateUserController::class, 'logout'])->middleware('auth:sanctum');

//PostController routes
Route::get('/posts', [PostController::class, 'index']);
Route::group(['prefix' => 'post'], function () {
    Route::get('/{id}', [PostController::class, 'show']);
    Route::post('/create', [PostController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [PostController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [PostController::class, 'destroy'])->middleware('auth:sanctum');
});
