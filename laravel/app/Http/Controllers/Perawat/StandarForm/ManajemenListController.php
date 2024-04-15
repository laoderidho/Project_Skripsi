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

        $list = "SELECT DISTINCT
                    DATE_FORMAT(p.jam_pemberian_diagnosa, '%d-%m-%Y') AS tanggal_pemeriksaan,
                    CASE DAYOFWEEK(p.jam_pemberian_diagnosa)
                        WHEN 1 THEN 'Minggu'
                        WHEN 2 THEN 'Senin'
                        WHEN 3 THEN 'Selasa'
                        WHEN 4 THEN 'Rabu'
                        WHEN 5 THEN 'Kamis'
                        WHEN 6 THEN 'Jumat'
                        WHEN 7 THEN 'Sabtu'
                    END AS hari
                FROM
                    pemeriksaan p
                INNER JOIN
                    perawatan pr ON pr.id = p.id_perawatan
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
                us.nama_lengkap,
                p.shift
                FROM
                pemeriksaan p
                INNER JOIN perawatan pr ON pr.id = p.id_perawatan
                INNER JOIN perawat per ON p.id_perawat = per.id
                INNER JOIN users us ON us.id = per.id_user
                WHERE pr.id = $id_perawatan AND p.shift = $shift AND date_format(p.jam_pemberian_diagnosa, '%d-%m-%Y') = $tanggal";

            $displayDate = DB::select($displayDate);

            $currentDate = date('d-m-Y');

            $shift = date('H');

            if($shift >= 7 && $shift < 14){
                $shift = 1;
            }elseif($shift >= 14 && $shift < 21){
                $shift = 2;
            }else{
                $shift = 3;
            }

            foreach($displayDate as $d){
                if($perawat == $d->id_perawat && $d->tanggal_pemberian_diagnosa == $currentDate && $d->shift == $shift){
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

    public function chart()
    {

        $sum_pasien = DB::select("select count(*) as total_pasien from pasien")[0]->total_pasien;

        $perawatan_sembuh = DB::select("select count(*) as total_sembuh from perawatan where status_pasien = 'sembuh' ")[0]->total_sembuh;

        $perawatan_sakit = DB::select("select count(*) as total_sakit from perawatan where status_pasien = 'sakit'")[0]->total_sakit;

        $perawatan = DB::select("select count(*) as total_perawatan from perawatan")[0]->total_perawatan;

        $perawat = DB::select("select count(*) as total_perawat from perawat")[0]->total_perawat;

        $pasien = DB::select("select count(*) as total_pasien from pasien")[0]->total_pasien;

        return response()->json([
            'sum_pasien' => $sum_pasien,
            'perawatan_sembuh' => $perawatan_sembuh,
            'perawatan_sakit' => $perawatan_sakit,
            'perawatan' => $perawatan,
            'perawat' => $perawat,
            'pasien' => $pasien
        ], 200);
    }



    public function getDataPasien(){
        $users = Auth::user();
        $users = $users->id;

        $perawat = Perawat::where('id_user', $users)->first();
        $perawat = $perawat->id;

        $listPasien = "select p.nama_lengkap, pr.id, date_format(pem.jam_pemberian_diagnosa, '%d-%m-%Y') as tanggal_diagnosa, pem.shift
                        from pasien p
                        inner join perawatan pr on p.id = pr.id_pasien
                        inner join pemeriksaan pem on pr.id = pem.id_perawatan
                        where pem.id_perawat = $perawat AND (pem.jam_pemberian_intervensi IS NULL or pem.jam_pemberian_implementasi IS NULL or pem.jam_penilaian_luaran IS NULL or pem.jam_pemberian_evaluasi IS NULL)
                        AND DATE(pem.jam_pemberian_diagnosa) = CURDATE()";

        $listPasien = DB::select($listPasien);

        return response()->json([
            'listPasien' => $listPasien
        ]);
    }

}
