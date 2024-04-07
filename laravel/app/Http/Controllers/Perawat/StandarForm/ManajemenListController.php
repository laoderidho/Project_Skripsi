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

        $displayDate = "SELECT date_format(p.jam_pemberian_diagnosa, '%d-%m-%Y') as tanggal_pemberian_diagnosa,
                date_format(p.jam_pemberian_diagnosa, '%H:%i') as jam_pemberian_diagnosa,
                date_format(p.jam_pemberian_intervensi, '%d-%m-%Y') as tanggal_pemberian_intervensi,
                date_format(p.jam_pemberian_intervensi, '%H:%i') as jam_pemberian_intervensi,
                date_format(p.jam_pemberian_implementasi, '%d-%m-%Y') as tanggal_pemberian_implementasi,
                date_format(p.jam_pemberian_implementasi, '%H:%i') as jam_pemberian_implementasi,
                date_format(p.jam_penilaian_luaran, '%d-%m-%Y') as tanggal_pemberian_luaran,
                date_format(p.jam_penilaian_luaran, '%H:%i') as jam_pemberian_luaran,
                date_format(p.jam_pemberian_evaluasi, '%d-%m-%Y') as tanggal_pemberian_evaluasi,
                date_format(p.jam_pemberian_evaluasi, '%H:%i') as jam_pemberian_evaluasi,
                p.id,
                p.id_perawat,
                us.nama_lengkap
                FROM
                pemeriksaan p
                INNER JOIN perawatan pr ON pr.id = p.id_perawatan
                INNER JOIN perawat per ON p.id_perawat = per.id
                INNER JOIN users us ON us.id = per.id_user
                WHERE pr.id = $id_perawatan AND p.shift = $shift AND date_format(p.jam_pemberian_diagnosa, '%d-%m-%Y') = $tanggal";

            $displayDate = DB::select($displayDate);

            foreach($displayDate as $d){
                if($perawat == $d->id_perawat){
                    $d->access = true;
                }else{
                    $d->access = false;
                }
            }


        return response()->json($displayDate);
    }


    public function getShift(){
        if(date('H') >= 7 && date('H') < 14){
            $shift = 1;
        }elseif(date('H') >= 14 && date('H') < 21){
            $shift = 2;
        }else{
            $shift = 3;
        }

        return response()->json($shift);
    }


    public function getDatePerawatan($id){
        $date = "select id, date_format(tanggal_masuk , '%d-%m-%Y') as tanggal_masuk, date_format(tanggal_keluar, '%d-%m-%Y') as tanggal_keluar
                from perawatan
                where id_pasien = $id";

        $date = DB::select($date);

        return response()->json($date);
    }

    public function chart(){

        $sum_pasien = "select count(*) as total_pasien from pasien";
        $sum_pasien = DB::select($sum_pasien);

        $perawatan_sembuh = "select count(*) as total_sembuh from perawatan where status_pasien = 'sembuh' ";
        $perawatan_sembuh = DB::select($perawatan_sembuh);

        $perawatan_sakit = "select count(*) as total_sakit from perawatan where status_pasien = 'sakit'";
        $perawatan_sakit = DB::select($perawatan_sakit);

        $perawatan = "select count(*) as total_perawatan from perawatan";
        $perawatan = DB::select($perawatan);

        return response()->json([
            'sum_pasien' => $sum_pasien,
            'perawatan_sembuh' => $perawatan_sembuh,
            'perawatan_sakit' => $perawatan_sakit,
            'perawatan' => $perawatan
        ], 200);
    }
}
