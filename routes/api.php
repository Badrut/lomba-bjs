<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZiswafController;
use App\Http\Controllers\Api\NasabahController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\RekeningController;
use App\Http\Controllers\Api\InvestasiController;
use App\Http\Controllers\Api\PembiayaanController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FinancingCardController;
use App\Http\Controllers\Api\NisbahSettingController;
use App\Http\Controllers\Api\SystemSettingController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('users', UserController::class);
    Route::apiResource('nasabahs', NasabahController::class);
    Route::apiResource('rekenings', RekeningController::class);
    Route::apiResource('pembiayaans', PembiayaanController::class);
    Route::apiResource('investasis', InvestasiController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('financing-cards', FinancingCardController::class);
    Route::apiResource('nisbah-settings', NisbahSettingController::class);
    Route::apiResource('ziswafs', ZiswafController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('documents', DocumentController::class);
    Route::apiResource('system-settings', SystemSettingController::class);
    Route::apiResource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
});
