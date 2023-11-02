<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Perawat\PerawatController;
use App\Http\Controllers\PasienController;

//Controller Admin
use App\Http\Controllers\Admin\InputDiagnosaController;
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
});

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {

    Route::prefix('admin')->group(function(){
        Route::get('/', [AdminController::class, 'getAll']);
        Route::get('/getAll', [AdminController::class, 'getAll']);
        Route::get('/hello', [AdminController::class, 'hello']);
        Route::get('/view', [AdminController::class, 'getAll']);
        Route::get('/pasien', [PasienController::class,'index']);
        Route::post('/diagnosa/add', [InputDiagnosaController::class,'tambahDiagnosa']);
        Route::post('/pasien/create', [PasienController::class, 'store']);
    });

});

Route::middleware(['auth:sanctum', 'checkRole:perawat'])->group(function () {

    Route::prefix('perawat')->group(function(){
        Route::get('/', [PerawatController::class, 'hello']);
        // Route::get('/add_diagnosa'[])
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
