<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Perawat\PerawatController;

//Controller Admin
use App\Http\Controllers\Admin\Data\PasienController;
use App\Http\Controllers\Admin\InputDiagnosaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\DetailPenyebab;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\Diagnosa;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\FaktorResiko;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\Gejala;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\JenisGejala;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\JenisPenyebab;

//Intervensi
use App\Http\Controllers\Admin\StandardKeperawatan\Intervensi\TindakanIntervensi;
use App\Http\Controllers\Admin\StandardKeperawatan\Intervensi\KategoriTindakan;
use App\Http\Controllers\Admin\StandardKeperawatan\Intervensi\Intervensi;


//Luaran



//Controller Perawat

//

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/update-password', [AuthController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {

    Route::prefix('admin')->group(function(){
        Route::post('/', [AdminController::class, 'getAll']);
        Route::get('/getAll', [AdminController::class, 'getAll']);
        Route::get('/hello', [AdminController::class, 'hello']);
        Route::get('/view', [AdminController::class, 'getAll']);

        Route::prefix('user')->group(function(){
            Route::post('/', [UserController::class, 'getUsers']);
            Route::post('/delete/{id}', [UserController::class, 'delete']);
            Route::post('/update/{id}', [UserController::class, 'updateUser']);
        });


        Route::prefix('daftarpasien')->group(function () {
            Route::post('/tambah', [PasienController::class, 'addPasien']);
            Route::post('/', [PasienController::class, 'getPasien']);
            Route::post('/edit/{id}', [PasienController::class, 'update']);
            Route::post('/detail/{id}', [PasienController::class, 'getDetail']);
            Route::post('/delete/{id}', [PasienController::class, 'delete']);
        });

        Route::get('/pasien', [PasienController::class,'index']);

        //Diagnosa
        Route::post('/diagnosa/add', [InputDiagnosaController::class,'tambahDiagnosa']);
        Route::get('/diagnosa/{id}',[InputDiagnosaController::class,'getDiagnosa']);
        Route::post('/diagnosa/edit', [InputDiagnosaController::class,'editDiagnosa']);
        Route::post('/diagnosa/delete', [InputDiagnosaController::class,'hapusDiagnosa']);

        //Intervensi
        Route::post('/intervensi/add', [InputDiagnosaController::class,'tambahDiagnosa']);


        //Luaran

        Route::post('/pasien/create', [PasienController::class, 'store']);
    });

});

Route::middleware(['auth:sanctum', 'checkRole:perawat'])->group(function () {

    Route::prefix('perawat')->group(function(){
        Route::get('/', [PerawatController::class, 'hello']);
        // Route::get('/add_diagnosa'[])
    });
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/tambah', [UserController::class, 'addUser']);
