<?php

namespace App\Http\Controllers\Perawat\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// db suport
use Illuminate\Support\Facades\DB;

class AskepController extends Controller
{
    public function getReportAskep($id_pemeriksaan){

        $pasien = $this->getPasien($id_pemeriksaan);
        return response()->json([
            'status' => 'success',
            'pasien' => $pasien
        ]);

    }

    private function getPasien($id){
        $detailPasien = "select  p.id, ps.nama_lengkap, b.no_bed, p.tanggal_masuk, p.waktu_pencatatan, p.tanggal_keluar, p.waktu_keluar, b.nama_fasilitas, b.jenis_ruangan, b.lantai
                            from pasien ps
                            join perawatan p on p.id_pasien =ps.id
                            join beds b on p.bed = b.id
                            where p.id = $id";
        $detailPasien = DB::select($detailPasien);

        return $detailPasien;
    }

    private function getPemeriksaan($id){

    }
}
