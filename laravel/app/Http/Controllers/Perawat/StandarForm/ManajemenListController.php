<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Perawat\Perawatan;
use App\Models\Admin\Pasien;
use App\Models\Perawat\Pemeriksaan;
// db suport
use Illuminate\Support\Facades\DB;
// auth suport
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Perawat;

class ManajemenListController extends Controller
{
    public function setNameWithPerawatan($perawatan_id)
    {
        $perawatan = Perawatan::where('id', $perawatan_id)->first();
        $pasien = $perawatan->pasien;

        $name = $pasien->nama_lengkap;

        return response()->json([
            'message' => 'success',
            'name' => $name,
        ]);
    }

    public function listTimeAskep($id_perawatan)
    {
        $users = Auth::user();
        $users = $users->id;
        $perawat = Perawat::where('id_user', $users)->first();
        $perawat = $perawat->id;

        $pemeriksaan = Pemeriksaan::where('id_perawat', $perawat)->get();

        if ($pemeriksaan->isEmpty()) {
            return response()->json([
                'message' => 'Data Kosong',
            ], 200);
        }


        $displayDate =
        DB::table('pemeriksaan as p')
        ->select(
            'p.jam_pemberian_diagnosa',
            'p.jam_pemberian_intervensi',
            'p.jam_penilaian_luaran',
            'p.jam_pemberian_evaluasi',
            'p.jam_pemberian_implementasi',
            DB::raw("DATE_FORMAT(fd.updated_at, '%d-%m-%Y') as tanggal"),
            DB::raw("CASE DAYOFWEEK(fd.updated_at)
                            WHEN 1 THEN 'Minggu'
                            WHEN 2 THEN 'Senin'
                            WHEN 3 THEN 'Selasa'
                            WHEN 4 THEN 'Rabu'
                            WHEN 5 THEN 'Kamis'
                            WHEN 6 THEN 'Jumat'
                            WHEN 7 THEN 'Sabtu'
                        END as Hari"),
            'p.id'
        )
            ->join('form_diagnosa as fd', 'p.id', '=', 'fd.id_pemeriksaan')
            ->join('perawatan as pr', 'pr.id', '=', 'p.id_perawatan')
            ->where('pr.id', 1)
            ->get();

        return response()->json($displayDate);
    }
}
