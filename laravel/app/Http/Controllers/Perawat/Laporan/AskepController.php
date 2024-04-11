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
            $diagnosa = $diagnosa->getDetailDiagnosaPasien($value->id);
            $diagnosa = $diagnosa->getData();

            $intervensi = new IntervensiFormController();
            $intervensi = $intervensi->getDetailIntervensi($value->id);
            $intervensi = $intervensi->getData();

            $luaran = new LuaranFormController();
            $luaran = $luaran->detailAskepLuaran($value->id);
            $luaran = $luaran->getData();

            $evaluasi = new EvaluasiController();
            $evaluasi = $evaluasi->getDetailLuaran($value->id);
            $evaluasi = $evaluasi->getData();

            $pemeriksaan[$key]->diagnosa = $diagnosa;
            $pemeriksaan[$key]->intervensi = $intervensi;
            $pemeriksaan[$key]->luaran = $luaran;
            $pemeriksaan[$key]->implementasi = $this->getImplementasiData($value->id);
            $pemeriksaan[$key]->evaluasi = $evaluasi;

        }
        return response()->json([
            'status' => 'success',
            'pasien' => $pasien,
            'pemeriksaan' => $pemeriksaan
        ]);

    }

    private function getPasien($id){
        $detailPasien = "select  p.id, ps.nama_lengkap, b.no_bed
                        from pasien ps
                        join perawatan p on p.id_pasien =ps.id
                        join beds b on p.bed = b.id
                        where p.id = $id";

        $detailPasien = DB::select($detailPasien);

        return $detailPasien;
    }

    private function getPemeriksaan($id){
        $data = "select p.id, p.nama_luaran, p.catatan_intervensi, p.catatan_evaluasi, p.catatan_luaran, p.catatan_implementasi, date_format(p.jam_pemberian_diagnosa, '%d-%m-%Y') as tanggal_pemeriksaan, date_format(p.jam_pemberian_diagnosa, '%H:%i') as jam_pemeriksaan,
                p.jam_pemberian_intervensi, p.jam_pemberian_implementasi, p.jam_penilaian_luaran, p.shift
                from perawatan pr join pemeriksaan p
                on pr.id = p.id_perawatan
                where pr.id = $id";

        $data = DB::select($data);

        return $data;
    }

    private function getImplementasiData($id){
        $data = "select i.id, ti.nama_tindakan_intervensi as nama_implementasi, i.tindakan_implementasi
                                from form_implementasi i
                                join tindakan_intervensi ti
                                on i.nama_implementasi = ti.id
                                join kategori_tindakan kt
                                on ti.id_kategori_tindakan = kt.id
                                where i.id_pemeriksaan = $id";
        $data = DB::select($data);

        return $data;
    }


    public function getDatePerawatan($id){
        $datePerawatan = "select pr.id, pr.tanggal_masuk
                        from perawatan pr
                        inner join pasien p
                        on  pr.id_pasien = p.id
                        where id_pasien = $id";

        $datePerawatan = DB::select($datePerawatan);

        return response()->json([
            'status' => 'success',
            'data' => $datePerawatan
        ]);
    }
}
