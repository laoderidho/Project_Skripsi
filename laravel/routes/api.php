<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Perawat\PerawatController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {

    Route::prefix('admin')->group(function(){
        Route::get('/', [AdminController::class, 'getAll']);
        Route::get('/getAll', [AdminController::class, 'getAll']);
        Route::get('/hello', [AdminController::class, 'hello']);
    });

});

Route::middleware(['auth:sanctum', 'checkRole:perawat'])->group(function () {

    Route::prefix('perawat')->group(function(){
        Route::get('/hello', [PerawatController::class, 'hello']);
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
