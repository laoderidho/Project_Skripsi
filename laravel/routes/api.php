<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Perawat\PerawatController;
use App\Http\Controllers\Perawat\Diagnostic\DiagnosticController;
use App\Http\Controllers\Perawat\Laporan\AskepController;
//Controller Admin
use App\Http\Controllers\Admin\Data\PasienController;
use App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa\InputDiagnosaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\InputLuaranController;
use App\Http\Controllers\Admin\StandardKeperawatan\Intervensi\IntervensiController;
//Controller Perawat
use App\Http\Controllers\Perawat\StandarForm\DiagnosaController;
use App\Http\Controllers\Perawat\StandarForm\IntervensiFormController;
use App\Http\Controllers\Perawat\StandarForm\InputImplementasiController;
use App\Http\Controllers\Admin\Data\BedController;
use App\Http\Controllers\LuaranController;
use App\Http\Controllers\Perawat\PerawatanController;
use App\Http\Controllers\Perawat\StandarForm\LuaranFormController;
use App\Http\Controllers\Perawat\StandarForm\ManajemenListController;
use App\Http\Controllers\Perawat\StandarForm\EvaluasiController;
use App\Http\Controllers\Perawat\ProfileController;
use App\Http\Controllers\AmananessaController;
use App\Http\Controllers\Admin\AdminLogController;

//

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-password', [AuthController::class, 'changePassword']);
    Route::post('/amnanessa/detail/{id}', [AmananessaController::class, 'detail']);
});

Route::middleware(['auth:sanctum', 'checkRole:admin'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::post('/statistic', [ManajemenListController::class, 'chart']);
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

        Route::prefix('bed')->group((function () {
            Route::post('/', [BedController::class, 'getBed']);
            Route::post('/tambah', [BedController::class, 'addBed']);
            Route::post('/filter', [BedController::class, 'filterBed']);
            Route::post('/edit/{id}', [BedController::class, 'editBed']);
            Route::post('/delete/{id}', [BedController::class, 'deleteBed']);
        }));

        Route::prefix('ruangan')->group((function () {
            Route::post('/filter-bed/{no_kamar}', [BedController::class, 'filterBedWithAll']);
            Route::post('/nama-fasilitas', [BedController::class, 'getNamaFasilitas']);
            Route::post('/jenis-ruangan', [BedController::class, 'getJenisRuangan']);
            Route::post('/lantai', [BedController::class, 'getLantai']);
        }));

        Route::prefix('intervensi')->group(function () {
            Route::post('/', [IntervensiController::class, 'getIntervensi']);
            Route::post('/tambah', [IntervensiController::class, 'AddIntervensi']);
            Route::post('/edit/{id}', [IntervensiController::class, 'updateIntervensi']);
            Route::post('/detail/{id}', [IntervensiController::class, 'detailIntervensi']);
            Route::post('/delete/{id}', [IntervensiController::class, 'deleteIntervensi']);
        });


        Route::prefix('pasien')->group(function () {
            Route::post('/', [PasienController::class, 'index']);
            Route::post('/rawat-inap/{id}', [PasienController::class, 'addRawatInap']);
            Route::post('/create', [PasienController::class, 'store']);
            Route::post('/tanggal-rawat/{id}', [PasienController::class, 'getDateRawatInapPasien']);
            Route::post('/rawat-inap', [PasienController::class, 'pasienRawatInap']);
        });

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
            Route::post('/detail/{id}', [InputLuaranController::class, 'detailLuaran']);
            Route::post('/update/{id_luaran}', [InputLuaranController::class, 'update']);
            Route::post('/delete/{id_luaran}', [InputLuaranController::class, 'delete']);
        });

        Route::prefix('rawat-inap')->group(function () {
            Route::post('/delete/{id}', [PasienController::class, 'deleteRawatInap']);
            Route::post('/detail/{id}', [PasienController::class, 'getDetailRawatInap']);
            Route::post('/update/{id}', [PasienController::class, 'UpdateRawatInap']);
            Route::post('/recover/{id}', [PasienController::class, 'updatePasienRecover']);
        });

        Route::post('/create', [PasienController::class, 'store']);

        Route::prefix('laporan')->group(function () {
            Route::post('/date-perawatan/{id}', [ManajemenListController::class, 'getDatePerawatan']);
            Route::post('/askep/{id_perawatan}', [AskepController::class, 'getReportAskep']);
        });


        Route::prefix('amnanessa')->group(function () {
            Route::post('/add/{id}', [AmananessaController::class, 'add']);
            Route::post('/edit/{id}', [AmananessaController::class, 'edit']);
        });

        Route::post('/log-admin', [AdminLogController::class, 'getDatalogAdmin']);
    });
});

Route::middleware(['auth:sanctum', 'checkRole:perawat'])->group(function () {

    Route::prefix('perawat')->group(function () {

        Route::prefix('diagnosa')->group(function () {
            Route::post('/', [DiagnosaController::class, 'getDiagnosa']);
            Route::post('/detail/{id}', [DiagnosaController::class, 'validationDiagnosaAttribute']);

            // form diagnosa
            Route::post('/add/{id}', [DiagnosaController::class, 'addPasienDiagnosa']);

            // detail askep diagnosa
            Route::post('/detail-askep-pasien/{id}', [DiagnosaController::class, 'getDetailDiagnosaPasien']);
        });

        Route::post('/list-askep/{id}/{shift}/{tanggal}', [ManajemenListController::class, 'listTimeAskep']);

        // perawat/luaran/detail/{id}
        Route::prefix('luaran')->group(function () {
            Route::post('/', [InputLuaranController::class, 'getLuaran']);
            Route::post('/detail/{id}', [LuaranFormController::class, 'validationLuaranAttribute']);
            Route::post('/add/{id}', [LuaranFormController::class, 'add']);

            Route::post('/detail-askep-luaran/{id}', [LuaranFormController::class, 'detailAskepLuaran']);
        });


        Route::prefix('evaluasi')->group(function () {
            Route::post('/get/{id_pemeriksaan}', [EvaluasiController::class, 'getLuaran']);
            Route::post('/penilaian-luaran', [EvaluasiController::class, 'PenilaianLuaran']);
            Route::post('/hasil-evaluasi/{id_pemeriksaan}', [EvaluasiController::class, 'resultEvaluasi']);
            Route::post('/detail/{id_pemeriksaan}', [EvaluasiController::class, 'getDetailLuaran']);
        });

        Route::prefix('intervensi')->group(function () {
            Route::post('/', [IntervensiController::class, 'getIntervensi']);
            Route::post('/detail/{id}', [IntervensiFormController::class, 'validationIntervensiAttribute']);
            Route::post('/update/{id_pemeriksaan}', [IntervensiFormController::class, 'updateIntervensi']);
            Route::post('/detail-pasien-intervensi/{id_pemeriksaan}', [IntervensiFormController::class, 'getDetailIntervensi']);
        });


        Route::prefix('implementasi')->group(function () {
            Route::post('/chekclist/{id}', [InputImplementasiController::class, 'checkList']);
            Route::post('/get-implementasi-pasien/{id_pemeriksaan}', [InputImplementasiController::class, 'getImplementasiPasien']);
            Route::post('/isDone/{id}', [InputImplementasiController::class, 'isDone']);
        });

        Route::prefix('daftarpasien')->group(function () {
            Route::post('/', [PasienController::class, 'getPasien']);
            Route::post('/status=0',[PasienController::class, 'getdataPasienRawatInap']);
            Route::post('/rawat-inap', [PasienController::class, 'filterStatusRawatInap']);
            Route::post('/detail/{id}', [PasienController::class, 'getDetail']);
            // Route::post('/delete/{id}', [PasienController::class, 'delete']);
        });

        Route::prefix('diagnostic')->group(function () {
            Route::post('/add/{id}', [DiagnosticController::class, 'addDiagnostic']);
            Route::post('/', [DiagnosticController::class, 'index']);
            Route::post('/get/{id}', [DiagnosticController::class, 'getDiagnostic']);
            Route::post('/getlist/{id}', [DiagnosticController::class, 'getListDiagnostik']);
        });

        Route::prefix('listaskep')->group(function () {
            Route::post('/setname/{id}', [ManajemenListController::class, 'setNameWithPerawatan']);
            Route::post('/list-pemeriksaan/{id}', [ManajemenListController::class, 'listPemeriksaan']);
        });

        Route::prefix('shift')->group(function () {
            Route::post('/', [ManajemenListController::class, 'getShift']);
        });

        Route::post('/profile', [ProfileController::class, 'profile']);
        Route::post('/my-pasien', [ManajemenListController::class, 'getDataPasien']);
    });
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
Route::post('/tambah', [UserController::class, 'addUser']);

Route::get('/home', function () {
    return view('home');
});
