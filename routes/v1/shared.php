<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicManagement\ItemController;
use App\Http\Controllers\ClinicManagement\PermissionController;
use App\Http\Controllers\Doctor\ReservationController;
use App\Http\Controllers\Secretary\PatientController;
use App\Http\Controllers\Secretary\TreatementController;
use App\Http\Controllers\Patient\ReservationController as PatientReservationController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function() {
    Route::post('login', 'login');
    Route::middleware('auth:api')->group(function() {
        Route::delete('logout', 'logout');
        Route::put('change-password', 'changePassword');
        Route::put('update-phone', 'updatePhone');
        Route::get('my-profile', 'myProfile');
        Route::put('update-profile', 'updateMyProfile');
    });
    Route::get('refresh-token', 'refreshToken')->middleware('jwt.refresh');
});

Route::middleware('auth:api')->group(function () {
    Route::get('doctors', [TreatementController::class, 'doctors']);
    Route::get('patients', [PatientController::class, 'index']);
    Route::get('patient/financial-report', [PatientReservationController::class, 'export']);
    Route::get('items-report', [ItemController::class, 'export']);

    Route::prefix('permission')->group(function () {
        Route::get('', [PermissionController::class, 'index']);
    });
});