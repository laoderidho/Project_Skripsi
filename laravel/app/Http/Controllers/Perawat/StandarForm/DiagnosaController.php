<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Admin\DetailPenyebab;
use App\Models\Admin\Diagnosa;
use App\Models\Admin\FaktorResiko;
use App\Models\Admin\Gejala;
use App\Models\Admin\JenisPenyebab;
use Illuminate\Http\Request;

class DiagnosaController extends Controller
{
    public function getDiagnosa(){
        $diagnosa = Diagnosa::all();

        return response()->json([
            'message' => 'Success',
            'data' => $diagnosa,
        ]);
    }

    public function validationDiagnosaAttribute($id){
        $diagnosa = Diagnosa::find($id);

        if($diagnosa == null){
            return response()->json([
                'message' => 'Diagnosa tidak ditemukan',
            ], 404);
        }

        $gejala_tanda_mayor_subjektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 1)->where('id_kategori_gejala', 1)->get();
        $gejala_tanda_mayor_objektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 1)->where('id_kategori_gejala', 2)->get();

        $gejala_tanda_minor_subjektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 2)->where('id_kategori_gejala', 1)->get();
        $gejala_tanda_minor_objektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 2)->where('id_kategori_gejala', 2)->get();

        $faktor_risiko = FaktorResiko::where('id_diagnosa', $diagnosa->id)->get();

        $penyebab_psikologis = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 1)->get();
        $penyebab_situasional = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 2)->get();
        $penyebab_fisiologis = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 3)->get();


        return response()->json([
            'message' => 'Success',
            'diagnosa' => $diagnosa,
            'gejala_tanda_mayor_subjektif' => $gejala_tanda_mayor_subjektif,
            'gejala_tanda_mayor_objektif' => $gejala_tanda_mayor_objektif,
            'gejala_tanda_minor_subjektif' => $gejala_tanda_minor_subjektif,
            'gejala_tanda_minor_objektif' => $gejala_tanda_minor_objektif,
            'faktor_risiko' => $faktor_risiko,
            'penyebab_psikologis' => $penyebab_psikologis,
            'penyebab_situasional' => $penyebab_situasional,
            'penyebab_fisiologis' => $penyebab_fisiologis,
        ]);
    }
}
