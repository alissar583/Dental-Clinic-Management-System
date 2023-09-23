<?php

use App\Http\Controllers\ClinicManagement\DoctorController;
use App\Http\Controllers\Secretary\CancellationRequestsController;
use App\Http\Controllers\Secretary\PatientController;
use App\Http\Controllers\Secretary\ReservationController;
use App\Http\Controllers\Secretary\TreatementController;
use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/shared.php';

Route::prefix('treatements')->controller(TreatementController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
});


Route::prefix('reservation')->controller(ReservationController::class)->group(function () {
    Route::post('/{treatement}', 'store');
    Route::get('/', 'index');
    Route::post('cancel/{reservation}', 'cancelReservation');
    Route::put('/{reservation}', 'update');
});

Route::prefix('reservation/cancellation-requests')->controller(CancellationRequestsController::class)->group(function() {
    Route::get('', 'index');
    Route::put('refuse/{cancellationRequest}', 'refuseRequest');
    Route::put('confirm/{cancellationRequest}', 'confirmRequest');
});

Route::get('doctors', [TreatementController::class, 'doctors']);

Route::get('get-available-times-by-month/{doctor}', [ReservationController::class, 'getAvailableTimesByMonth']);
Route::get('previews', [ReservationController::class, 'previews']);

Route::post('account', [PatientController::class, 'createAccount']);

Route::get('illness', [PatientController::class, 'getIllnesses']);
Route::get('patients', [PatientController::class, 'index']);
