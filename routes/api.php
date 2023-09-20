<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\QuestionController;
use App\Http\Controllers\Course\TopicController;
use App\Http\Controllers\Exam\ClientController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\User\UserController;
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
Route::any('/major', [MajorController::class, 'crud'])->middleware('auth:api');
Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::any('/', [UserController::class, 'crud']);
});
Route::group(['prefix' => 'course', 'middleware' => 'auth:api'], function () {
    Route::any('/', [CourseController::class, 'crud']);
    Route::group(['prefix' => 'topic'], function () {
        Route::any('/', [TopicController::class, 'crud']);
        Route::group(['prefix' => 'question'], function () {
            Route::any('/', [QuestionController::class, 'crud']);
        });
    });
});

