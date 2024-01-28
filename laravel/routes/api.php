<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Perawat\PerawatController;

//Controller Admin
use App\Http\Controllers\Admin\Data\PasienController;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\InputDiagnosaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\InputLuaranController;
use App\Http\Controllers\Admin\StandardKeperawatan\Intervensi\IntervensiController;
//Controller Perawat
use App\Http\Controllers\Perawat\StandarForm\DiagnosaController;
use App\Http\Controllers\Perawat\StandarForm\IntervensiFormController;
use App\Http\Controllers\Perawat\StandarForm\Diagnostic\DiagnosticController;

//

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-password', [AuthController::class, 'changePassword']);
    Route::post('/pasien/rawat-inap/{id}', [PasienController::class, 'addRawatInap']);
    Route::post('/pasien/rawat-inap/detailStatus/{id}', [PasienController::class, 'getStatusRawatInap']);
});

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::post('/', [AdminController::class, 'getAll']);
        Route::post('/getAll', [AdminController::class, 'getAll']);
        Route::post('/hello', [AdminController::class, 'hello']);
        Route::post('/view', [AdminController::class, 'getAll']);

        Route::prefix('users')->group(function () {
            Route::post('/tambah', [UserController::class, 'addUser']);
            Route::post('/', [UserController::class, 'getUsers']);
            Route::post('/delete/{id}', [UserController::class, 'delete']);
            Route::post('/update/{id}', [UserController::class, 'updateUser']);
            Route::post('/detail/{id}', [UserController::class, 'detailUser']);
        });

        Route::prefix('daftarpasien')->group(function () {
            Route::post('/tambah', [PasienController::class, 'addPasien']);
            Route::post('/', [PasienController::class, 'getPasien']);
            Route::post('/edit/{id}', [PasienController::class, 'update']);
            Route::post('/detail/{id}', [PasienController::class, 'getDetail']);
            Route::post('/delete/{id}', [PasienController::class, 'delete']);
        });

        Route::prefix('intervensi')->group(function () {
            Route::post('/', [IntervensiController::class, 'getIntervensi']);
            Route::post('/tambah', [IntervensiController::class, 'AddIntervensi']);
            Route::post('/edit/{id}', [IntervensiController::class, 'updateIntervensi']);
            Route::post('/detail/{id}', [IntervensiController::class, 'detailIntervensi']);
            Route::post('/delete/{id}', [IntervensiController::class, 'deleteIntervensi']);
        });


        Route::post('/pasien', [PasienController::class, 'index']);

        //Diagnosa

        Route::prefix('diagnosa')->group(function () {
            Route::post('/', [InputDiagnosaController::class, 'getDiagnosa']);
            Route::post('/detail/{id}', [InputDiagnosaController::class, 'detailDiagnosa']);
            Route::post('/tambah', [InputDiagnosaController::class, 'AddDiagnosa']);
            Route::post('/update/{id}', [InputDiagnosaController::class, 'updateDiagnosa']);
            Route::post('/delete/{id}', [InputDiagnosaController::class, 'hapusDiagnosa']);
        });


        //Luaran

        Route::prefix('luaran')->group(function () {
            Route::post('/', [InputLuaranController::class, 'getLuaran']);
            Route::post('/add', [InputLuaranController::class, 'createLuaran']); // Move this line above the next line
            Route::post('/{id}', [InputLuaranController::class, 'detailLuaran']);
            Route::post('/update/{id_luaran}', [InputLuaranController::class, 'update']);
            Route::post('/delete/{id_luaran}', [InputLuaranController::class, 'delete']);
        });


        Route::post('/pasien/create', [PasienController::class, 'store']);
    });
});

Route::middleware(['auth:sanctum', 'checkRole:perawat'])->group(function () {

    Route::prefix('perawat')->group(function () {
        Route::post('/', [PerawatController::class, 'hello']);

        Route::prefix('diagnosa')->group(function () {
            Route::post('/', [DiagnosaController::class, 'getDiagnosa']);
            Route::post('/detail/{id}', [DiagnosaController::class, 'validationDiagnosaAttribute']);
        });
        Route::prefix('intervensi')->group(function(){
            Route::post('/', [IntervensiController::class, 'getIntervensi']);
        });

        Route::prefix('daftarpasien')->group(function () {
            Route::post('/tambah', [PasienController::class, 'addPasien']);
            Route::post('/', [PasienController::class, 'getPasien']);
            Route::post('/edit/{id}', [PasienController::class, 'update']);
            Route::post('/detail/{id}', [PasienController::class, 'getDetail']);
            Route::post('/delete/{id}', [PasienController::class, 'delete']);

        });
        Route::prefix('diagnostic')->group(function(){
            Route::post('/add',[DiagnosticController::class,'addDiagnostic']);
            Route::post('/get',[DiagnosticController::class,'getDiagnostic']);
            Route::post('/update',[DiagnosticController::class,'updateDiagnostic']);
            Route::post('/delete',[DiagnosticController::class,'deleteDiagnostic']);
        });

        Route::prefix('pasien')->group(function () {
            Route::post('/', [PasienController::class, 'filterStatusRawatInap']);
            Route::post('/detail/{id}', [PasienController::class, 'getDetail']);
        });
    });
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/tambah', [UserController::class, 'addUser']);

 Route::get('/home', function () {
    return view('home');
});
