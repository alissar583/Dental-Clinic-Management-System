<?php

use App\Http\Controllers\ClinicManagement\ItemController;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Doctor\PreviewController;
use App\Http\Controllers\Doctor\ReservationController;
use App\Http\Controllers\Doctor\TreatmentController;
use App\Http\Controllers\Doctor\WorkingDayController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/shared.php';


Route::prefix('previews')->controller(PreviewController::class)->group(function () {
    Route::get('/', 'index');
});

Route::prefix('reservation')->controller(ReservationController::class)->group(function () {
    Route::get('get-my-reservations', 'getMyReservations');
    Route::post('cancel/{reservation}', 'cancelReservation');
    Route::get('/{reservation}','show');
    Route::put('/{reservation}','update');
});

Route::prefix('treatement')->controller(TreatmentController::class)->group(function () {
    Route::get('/get-my-treatments', 'getMyTreatmemts');
    Route::post('/', 'store');
});

Route::get('working_days', [WorkingDayController::class, 'show']);

Route::prefix('items')->controller(ItemController::class)->group(function() {
    Route::get('', 'index');
    Route::delete('/{quantity}', 'decreaseItemQuantity');
    Route::get('get-by-quantity/{quantity}', 'getItemByQuantity');
});

Route::get('patient/medical-report/{patient}',[PatientController::class,'getPatientMedicalReport']);