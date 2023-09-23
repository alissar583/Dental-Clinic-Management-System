<?php

use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Patient\ReservationController;
use App\Http\Controllers\Patient\TreatementController;
use Illuminate\Support\Facades\Route;




Route::prefix('treatement')->controller(TreatementController::class)->group(function () {
    Route::get('get-my-treatements', 'getMyTreatements');
});
Route::prefix('reservations')->controller(ReservationController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/add-media/{reservation}', 'addMedia');
    Route::get('/{reservation}', 'show');
    Route::prefix('cancel-request')->group(function () {
        Route::get('/my-requests', 'getPatientCancelRequests');
        Route::post('/{reservation}', 'cancelRequest');
    });
});

Route::get('my-medical-report', [PatientController::class, 'getMyMedicalReport']);


Route::get('doctors', [TreatementController::class, 'doctors']);
