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
                    $d->acces = true;
                }else{
                    $d->acces = false;
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
}
