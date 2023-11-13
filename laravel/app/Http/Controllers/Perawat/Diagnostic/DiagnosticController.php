<?php

namespace App\Http\Controllers\Perawat\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DiagnosticController extends Controller
{
    public function addDiagnostic(Request $request, $id){
       $pasien = Pasien::find($id);

       $validator = Validator::make($request->all(),[
        'keluhan_utama'=> 'required|string|max:255',
        'riwayat_penyakit'=>'',
        'riwayat_alergi'=>'',
        'resiko_jatuh'=>'',
        'resiko_nyeri' => '',
        'suhu'=>'',
        'tekanan_darah'=>'',
        'nadi'=>'',
        'laju_respirasi'=>'',
        'kesadaran'=>'',
        'gcs_eye' => '',
        'gcs_motoric' => '',
        'gcs_visual' => '',
        'pemeriksaan_fisik'=>'',
       ]);

       if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
       }

       
    }
}
