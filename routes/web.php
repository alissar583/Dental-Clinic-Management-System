<?php

use App\Enums\ReservationStatus;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Patient\ReservationController;
use App\Models\Item;
use App\Models\Quantity;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('patient/medical-report/{patient}', [PatientController::class, 'getPatientMedicalReport']);

Route::get('date' , function() {
    $startDate = '2023-08-01'; // Start date
$endDate = '2023-08-10'; // End date

$dates = [];
$currentDate = Carbon::parse($startDate);

while ($currentDate <= Carbon::parse($endDate)) {
    $dates[] = $currentDate->format('Y-m-d');
    $currentDate->addDay();
}
});
