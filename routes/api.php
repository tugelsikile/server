<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Exam\ClientController;
use App\Http\Controllers\Exam\ExamController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::any('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});
Route::group(['prefix' => 'exam', 'middleware' => 'auth:api'], function () {
    Route::any('/', [ExamController::class, 'crud']);
    Route::group(['prefix' => 'client'], function () {
        Route::any('/', [ClientController::class, 'crud']);
    });
});
