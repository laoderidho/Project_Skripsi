<?php

namespace App\Http\Controllers\Perawat\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pasien;
use App\Models\Perawat\Diagnostic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// db suport
use Illuminate\Support\Facades\DB;


class DiagnosticController extends Controller
{
    public function addDiagnostic(Request $request, $id){
       $pasien = Pasien::find($id);

       $validator = Validator::make($request->all(),[
        'keluhan_utama'=> 'required|string|max:255',
        'penyebab_umum'=> 'required|string|max:255',
        'riwayat_penyakit'=>'string|max:255',
        'riwayat_alergi'=>'string|max:255',
        'risiko_jatuh'=>'string|max:255',
        'risiko_nyeri' => 'string|max:255',
        'suhu'=>'required|int|max:255',
        'tekanan_darah'=>'required|int',
        'sistolik' =>'required|int',
        'diastolik' => 'required|int',
        'nadi'=>'required|int',
        'laju_respirasi'=>'required|int',
        'eye' => 'required|int',
        'motor' => 'required|int',
        'visual' => 'required|int',
        'pemeriksaan_fisik'=> 'required|string|max:255',
       ]);

       if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
       }

       DB::beginTransaction();
       try{
        $diagnostic = new Diagnostic();
        $diagnostic -> keluhan_utama = $request -> input('keluhan_utama');
        $diagnostic -> riwayat_penyakit = $request -> input('riwayat_penyakit');
        $diagnostic -> riwayat_alergi= $request -> input('riwayat_alergi');
        $diagnostic -> risiko_jatuh= $request -> input('risiko_nyeri');
        $diagnostic -> suhu= $request -> input('suhu');
        $diagnostic -> tekanan_darah= $request -> input('tekanan_darah');
        $diagnostic -> sistolik= $request -> input('sistolik');
        $diagnostic -> diastolik= $request -> input('diastolik');
        $diagnostic -> nadi= $request -> input('nadi');
        $diagnostic -> laju_respirasi = $request -> input('laju_respirasi');
        $diagnostic -> eye= $request -> input('eye');
        $diagnostic -> motor= $request -> input('motor');
        $diagnostic -> visual= $request -> input('visual');
        $diagnostic -> pemeriksaan_fisik= $request -> input('pemeriksaan_fisik');

        $diagnostic->save();

       }
       catch(\Exception $e){
        dd($e);
        DB::rollback();
       }
       return response()->json(['message' => 'Data Diagnostik berhasil ditambahkan', 'data' => $diagnostic]);
    }
    public function getDiagnostic($id){
        $diagnostic = Diagnostic::find($id);
        if($diagnostic){
         return response()->json(['message' => 'Data Diagnostik berhasil ditemukan', 'data' => $diagnostic]);
        }
        return response()->json(['message' => 'Data Diagnostik tidak ditemukan', 'data' => null], 404);
     }

     public function updateDiagnostic(Request $request, $id){
        $diagnostic = Diagnostic::find($id);

        if($diagnostic){
         $validator = Validator::make($request->all(),[
             'keluhan_utama'=> 'required|string|max:255',
             'riwayat_penyakit'=>'string|max:255',
             'riwayat_alergi'=>'string|max:255',
             'risiko_jatuh'=>'string|max:255',
             'risiko_nyeri' => 'string|max:255',
             'suhu'=>'required|int|max:255',
             'tekanan_darah'=>'required|int',
             'sistolik' =>'required|int',
             'diastolik' => 'required|int',
             'nadi'=>'required|int',
             'laju_respirasi'=>'required|int',
             'eye' => 'required|int',
             'motor' => 'required|int',
             'visual' => 'required|int',
             'pemeriksaan_fisik'=> 'required|string|max:255',
            ]);

            if($validator->fails()){
             return response()->json($validator->errors()->toJson(), 400);
            }

            DB::beginTransaction();
            try{
             $diagnostic -> keluhan_utama = $request -> input('keluhan_utama');
             $diagnostic -> riwayat_penyakit = $request -> input('riwayat_penyakit');
             $diagnostic -> riwayat_alergi= $request -> input('riwayat_alergi');
             $diagnostic -> risiko_jatuh= $request -> input('risiko_nyeri');
             $diagnostic -> suhu= $request -> input('suhu');
             $diagnostic -> tekanan_darah= $request -> input('tekanan_darah');
             $diagnostic -> sistolik= $request -> input('sistolik');
             $diagnostic -> diastolik= $request -> input('diastolik');
             $diagnostic -> nadi= $request -> input('nadi');
             $diagnostic -> laju_respirasi = $request -> input('laju_respirasi');
             $diagnostic -> eye= $request -> input('eye');
             $diagnostic -> motor= $request -> input('motor');
             $diagnostic -> visual= $request -> input('visual');
             $diagnostic -> pemeriksaan_fisik= $request -> input('pemeriksaan_fisik');

             $diagnostic->save();

            }
            catch(\Exception $e){
             dd($e);
             DB::rollback();
            }
            return response()->json(['message' => 'Data Diagnostik berhasil diubah', 'data' => $diagnostic]);
         }
         return response()->json(['message' => 'Data Diagnostik tidak ditemukan', 'data' => null], 404);
     }

     public function deleteDiagnostic($id){
        $diagnostic = Diagnostic::find($id);
        if($diagnostic){
         $diagnostic->delete();
         return response()->json(['message' => 'Data Diagnostik berhasil dihapus', 'data' => $diagnostic]);
        }
        return response()->json(['message' => 'Data Diagnostik tidak ditemukan', 'data' => null], 404);
     }
}
