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


    public function listPemeriksaan($id){

        $list = "SELECT distinct DATE_FORMAT(p.jam_pemberian_diagnosa, '%d-%m-%Y') as tanggal_pemeriksaan
                FROM pemeriksaan p
                INNER JOIN perawatan pr ON pr.id = p.id_perawatan
                WHERE
                pr.id = $id";

        $list = DB::select($list);

        return response()->json($list);

    }

    public function listTimeAskep($id_perawatan, $shift, $tanggal)
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


            $displayDate = "SELECT date_format(p.jam_pemberian_diagnosa, '%d-%m-%Y') as tanggal_pemberian_diagnosa,
                date_format(p.jam_pemberian_diagnosa, '%H:%i') as jam_pemberian_diagnosa,
                p.jam_pemberian_intervensi,
                p.jam_penilaian_luaran,
                p.jam_pemberian_evaluasi,
                p.jam_pemberian_implementasi,
                p.id
                FROM
                pemeriksaan p
                inner join perawatan pr
                on pr.id = p.id_perawatan
                where pr.id = $id_perawatan and p.shift = $shift and date_format(p.jam_pemberian_diagnosa, '%d-%m-%Y') = $tanggal ";

            $displayDate = DB::select($displayDate);


        return response()->json($displayDate);
    }
}
