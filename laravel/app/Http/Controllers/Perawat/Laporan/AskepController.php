<?php

namespace App\Http\Controllers\Perawat\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Perawat\StandarForm\DiagnosaController;
use App\Http\Controllers\Perawat\StandarForm\IntervensiFormController;
use App\Http\Controllers\Perawat\StandarForm\EvaluasiController;
use App\Http\Controllers\Perawat\StandarForm\LuaranFormController;

// db suport
use Illuminate\Support\Facades\DB;

class AskepController extends Controller
{
    public function getReportAskep($id_perawatan){

        $pasien = $this->getPasien($id_perawatan);
        $pemeriksaan = $this->getPemeriksaan($id_perawatan);

        foreach ($pemeriksaan as $key => $value) {
            $diagnosa = new DiagnosaController();
            $diagnosa = $diagnosa->validationDiagnosaAttribute($value->id);

            $intervensi = new IntervensiFormController();
            $intervensi = $intervensi->getDetailIntervensi($value->id);

            $luaran = new LuaranFormController();
            $luaran = $luaran->detailAskepLuaran($value->id);

            $pemeriksaan[$key]->diagnosa = $diagnosa;
            $pemeriksaan[$key]->intervensi = $intervensi;
            $pemeriksaan[$key]->luaran = $luaran;
        }
        return response()->json([
            'status' => 'success',
            'pasien' => $pasien,
            'pemeriksaan' => $pemeriksaan
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
        $data = "select p.id, u.nama_lengkap, p.nama_intervensi,
                p.nama_luaran, p.catatan_intervensi, p.catatan_evaluasi, p.catatan_luaran, p.catatan_implementasi, p.jam_pemberian_diagnosa,
                p.jam_pemberian_intervensi, p.jam_pemberian_implementasi, p.jam_penilaian_luaran
                from perawatan pr join pemeriksaan p
                on pr.id = p.id_perawatan
                join perawat per
                on p.id_perawat = per.id
                join users u
                on per.id_user = u.id
                where pr.id = $id";

        $data = DB::select($data);

        return $data;
    }



    public function getPerawatan($id){
        
    }
}
