<?php

use App\Helpers\FileHelper;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicManagement\CategoryController;
use App\Http\Controllers\ClinicManagement\ClinicController;
use App\Http\Controllers\ClinicManagement\DoctorController;
use App\Http\Controllers\ClinicManagement\IllnessController;
use App\Http\Controllers\ClinicManagement\ItemController;
use App\Http\Controllers\ClinicManagement\MedicalReportController;
use App\Http\Controllers\ClinicManagement\PermissionController;
use App\Http\Controllers\ClinicManagement\PreviewController;
use App\Http\Controllers\ClinicManagement\RoleController;
use App\Http\Controllers\ClinicManagement\SecretaryController;
use App\Http\Controllers\ClinicManagement\SpecializationController as ClinicManagementSpecializationController;
use App\Http\Controllers\ClinicManagement\WorkingDaysController;
use App\Http\Controllers\Doctor\SpecializationController;
use App\Http\Controllers\Doctor\WorkingDayController;
use App\Http\Controllers\Patient\ReservationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

require __DIR__ . '/shared.php';


Route::middleware('cors')->group(function () {

    // Route::get('qrcode', function () {

    //     $qrCode = QrCode::size(200)
    //         ->format('png')
    //         ->generate('https://harrk.dev');

    //     $filename = 'qr-code.png';
        
    //     Storage::disk('public')->put($filename, $qrCode);

    //     $path = 'storage/app/public' . '/' . $filename;

    //     return asset($path);
    // });


    Route::prefix('clinic')->group(function () {
        Route::post('/', [ClinicController::class, 'store']);
        Route::put('/', [ClinicController::class, 'update']);
        Route::get('/', [ClinicController::class, 'show']);
        Route::prefix('working-days')->controller(WorkingDaysController::class)->group(function () {
            Route::post('/', 'store');
            Route::put('/', 'update');
            Route::delete('/{day}', 'destroy');
            Route::get('/', 'show');
        });
    });

    Route::get('/secretaries', [SecretaryController::class, 'index']);

    Route::prefix('account')->group(function () {
        Route::post('/', [AuthController::class, 'createAccount']);
        Route::get('/get-profile/{user}', [AuthController::class, 'getUserProfile']);
        Route::put('/update-profile/{user}', [AuthController::class, 'updateUserProfile']);
    });

    Route::prefix('role')->group(function () {
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/{role}', [RoleController::class, 'getPermissiosByRole']);
        Route::put('update-permissions/{role}', [RoleController::class, 'updateRolePermissions']);
        Route::delete('delete-permissions/{role}', [RoleController::class, 'deleteRolePermissions']);
    });



    Route::prefix('specialization')->group(function () {
        Route::controller(SpecializationController::class)->group(function () {
            Route::post('add-to-doctor/{doctor}', 'addSpecializationToDoctor');
            Route::delete('remove-from-doctor/{doctor}', 'removeSpecializationFromDoctor');
        });
        Route::controller(ClinicManagementSpecializationController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::put('/{specialization}', 'update');
            Route::delete('/{specialization}', 'destroy');
        });
    });

    Route::prefix('previews')->controller(PreviewController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{preview}', 'update');
        Route::delete('/{preview}', 'destroy');
    });


    Route::prefix('doctor')->controller(DoctorController::class)->group(function () {
        Route::get('', 'index');
        Route::prefix('previews')->group(function () {
            Route::get('/', 'getDoctorPreviews');
            Route::post('/{doctor}', 'addPreviewsToDoctor');
            Route::delete('/{doctor}', 'detachPreviewsFromDoctor');
        });
        Route::prefix('roles')->controller(AuthController::class)->group(function () {
            Route::post('/{user}', 'addRoleToUser');
        });
    });

    Route::prefix('doctor/working-days')->controller(WorkingDayController::class)->group(function () {
        Route::post('/{doctor}', 'store');
        Route::put('/{doctor}', 'update');
        Route::delete('/{day}', 'destroy');
        Route::get('/', 'show');
    });


    Route::prefix('medical-report')->controller(MedicalReportController::class)->group(function () {
        // Route::get('/{patient}', 'show');
        Route::put('update/{medicalReport}', 'update');
        Route::post('remove-illness/{medicalReport}', 'removeIllness');
    });

    Route::prefix('illness')->controller(IllnessController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{illness}', 'update');
        Route::delete('/{illness}', 'delete');
    });

    Route::prefix('category')->controller(CategoryController::class)->group(function() {
        Route::get('', 'index');
        Route::post('/store', 'store');
        Route::delete('/{category}', 'destroy');
    });

    Route::prefix('items')->controller(ItemController::class)->group(function() {
        Route::get('', 'index');
        Route::post('add-quantity/{item}', 'addQuantity');
        Route::post('', 'store');
        Route::delete('/{quantity}', 'decreaseItemQuantity');
        Route::get('get-by-quantity/{quantity}', 'getItemByQuantity');
        Route::delete('delete-quantity/{quantity}', 'deleteQuantity');
    });

});
