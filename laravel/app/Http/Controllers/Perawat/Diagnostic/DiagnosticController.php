<?php

namespace App\Http\Controllers\Perawat\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pasien;
use App\Models\Perawat\Diagnostic;
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

       $diagnostic = new Diagnostic([
            'id_pasien' => $pasien->id,
            'keluhan_utama' => $request->input('keluhan_utama'),
            'riwayat_penyakit' => $request->input('riwayat_penyakit'),
            'riwayat_alergi' => $request->input('riwayat_alergi'),
            'resiko_jatuh' => $request->input('resiko_jatuh'),
            'resiko_nyeri' => $request->input('resiko_nyeri'),
            'suhu' => $request->input('suhu'),
            'tekanan_darah' => $request->input('tekanan_darah'),
            'nadi' => $request->input('nadi'),
            'laju_respirasi' => $request->input('laju_respirasi'),
            'kesadaran' => $request->input('kesadaran'),
            'gcs_eyes' => $request->input('gcs_eyes'),
            'gcs_motoric' => $request->input('gcs_motoric'),
            'gcs_visual' => $request->input('gcs_visual'),
            'pemeriksaan_fisik' => $request->input('pemeriksaan_fisik'),
       ]);

         $diagnostic->save();

            return response()->json([
                'message' => 'Successfully created diagnostic!'
            ], 201);

    }
}
