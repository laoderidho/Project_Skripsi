<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Perawat\PerawatController;

//Controller Admin
use App\Http\Controllers\Admin\Data\PasienController;
use App\Http\Controllers\Admin\InputDiagnosaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\InputIntervensiController;
use App\Http\Controllers\Admin\InputLuaranController;
use App\Http\Controllers\Admin\StandardKeperawatan\Intervensi\IntervensiController;

use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\DetailPenyebab;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\Diagnosa;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\FaktorResiko;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\Gejala;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\Jenis;


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

        Route::prefix('users')->group(function(){
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

        Route::prefix('intervensi')->group(function(){
            Route::post('/', [IntervensiController::class, 'getIntervensi']);
            Route::post('/tambah', [IntervensiController::class, 'AddIntervensi']);
            Route::post('/edit/{id}', [IntervensiController::class, 'updateIntervensi']);
            Route::post('/detail/{id}', [IntervensiController::class, 'detailIntervensi']);
            Route::post('/delete/{id}', [IntervensiController::class, 'deleteIntervensi']);
        });

        Route::get('/pasien', [PasienController::class,'index']);

        //Diagnosa
        Route::post('/diagnosa/add', [InputDiagnosaController::class,'AddDiagnosa']);
        Route::get('/diagnosa/{id}',[InputDiagnosaController::class,'getDiagnosa']);
        Route::post('/diagnosa/edit', [InputDiagnosaController::class,'editDiagnosa']);
        Route::delete('/diagnosa/{id}', [InputDiagnosaController::class,'hapusDiagnosa']);

        //Luaran
// Rute untuk menampilkan detail luaran
        Route::post('/luaran/{kode_luaran}', [InputLuaranController::class, 'read']);
        // Rute untuk menangani pembuatan luaran
        Route::post('/luaran/add', [InputLuaranController::class, 'create']);
        // Rute untuk menangani pembaruan luaran
        Route::post('/luaran/{kode_luaran}', [InputLuaranController::class, 'update']);

        // Rute untuk menangani penghapusan luaran
        Route::delete('/luaran/{kode_luaran}', [InputLuaranController::class, 'delete']);


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
