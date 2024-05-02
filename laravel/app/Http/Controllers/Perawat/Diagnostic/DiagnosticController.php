<?php

namespace App\Http\Controllers\Perawat\Diagnostic;

use App\Http\Controllers\Controller;
use App\Models\Admin\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Perawat\Diagnostic;
use Illuminate\Support\Facades\Auth;


class DiagnosticController extends Controller
{

    public function addDiagnostic(Request $request, $id){

        $users = Auth::user();

        $perawat = DB::table('perawat')->where('id_user', $users->id)->first();

        $perawat = $perawat->id;

        $pasien = Pasien::find($id);
        $pasien = $pasien->id;
        if(!$pasien){
            return response()->json(['message' => 'Data Pasien tidak ditemukan', 'data' => null], 404);
        }

        $validator = Validator::make($request->all(),[
            'keluhan_tambahan' => 'nullable|string|max:255',
             'suhu'=> 'nullable|string|max:30',
             'tekanan_darah'=>'required|string',
             'kesadaran' => 'nullable|string|max:255',
             'sistolik' =>'required|int',
             'diastolik' => 'required|int',
             'nadi'=> 'nullable|string|max:255',
             'laju_respirasi'=> 'nullable|string|max:255',
             'eye' => 'nullable|string|max:255',
             'motor' => 'nullable|string|max:255',
             'verbal' => 'nullable|string|max:255',
             'pemeriksaan_fisik'=> 'nullable|string|max:1000',
            ]);

            if($validator->fails()){
             return response()->json($validator->errors()->toJson(), 400);
            }

       DB::beginTransaction();
       try{
        $diagnostic = new Diagnostic();
        $diagnostic->id_pasien = $pasien;
        $diagnostic->id_perawat = $perawat;

         $properties = [
            'keluhan_tambahan',
            'kesadaran',
            'suhu',
            'tekanan_darah',
            'sistolik',
            'diastolik',
            'nadi',
            'laju_respirasi',
            'eye',
            'motor',
            'verbal',
            'pemeriksaan_fisik'
            ];

        // Looping untuk mengatur nilai properti berdasarkan request
        foreach ($properties as $property) {
            // Memeriksa apakah request memiliki kunci yang sesuai dengan nama properti
            if ($request->has($property)!= null) {
                // Jika ada, maka atur nilai properti
                $diagnostic->$property = $request->input($property);
            }
        }

        $diagnostic->save();
        DB::commit();
        return response()->json(['message' => 'Data Diagnostik berhasil ditambahkan', 'data' => $diagnostic]);
       }
       catch(\Exception $e){
        dd($e);
        DB::rollback();
       }
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
            'id_pasien' => 'required|int',
            'id_perawat' => 'required|int',
            'keluhan_tambahan'=> 'string|max:255',
             'keluhan_utama'=> 'json|max:255',
             'riwayat_penyakit'=>'string|max:255',
             'riwayat_alergi'=>'string|max:255',
             'risiko_jatuh'=>'string|max:255',
             'risiko_nyeri' => 'string|max:255',
             'suhu'=>'required|int|max:255',
             'tekanan_darah'=>'required|int',
             'kesadaran' => 'required|string|max:255',
             'sistolik' =>'required|int',
             'diastolik' => 'required|int',
             'nadi'=>'required|int',
             'laju_respirasi'=>'required|int',
             'eye' => 'required|int',
             'motor' => 'required|int',
             'verbal' => 'required|int',
             'pemeriksaan_fisik'=> 'required|string|max:255',
            ]);

            if($validator->fails()){
             return response()->json($validator->errors()->toJson(), 400);
            }

            DB::beginTransaction();
            try{
             $diagnostic -> id_pasien = $request -> input('id_pasien');
             $diagnostic -> id_perawat = $request -> input('id_perawat');
             $diagnostic -> keluhan_utama = $request -> input('keluhan_utama');
             $diagnostic -> riwayat_penyakit = $request -> input('riwayat_penyakit');
             $diagnostic -> riwayat_alergi= $request -> input('riwayat_alergi');
             $diagnostic -> risiko_jatuh= $request -> input('risiko_nyeri');
             $diagnostic -> suhu= $request -> input('suhu');
             $diagnostic -> tekanan_darah= $request -> input('tekanan_darah');
             $diagnostic -> sistolik= $request -> input('sistolik');
             $diagnostic -> risiko_nyeri = $request -> input('risiko_nyeri');
             $diagnostic -> kesadaran = $request -> input('kesadaran');
             $diagnostic -> diastolik= $request -> input('diastolik');
             $diagnostic -> nadi= $request -> input('nadi');
             $diagnostic -> laju_respirasi = $request -> input('laju_respirasi');
             $diagnostic -> eye= $request -> input('eye');
             $diagnostic -> motor= $request -> input('motor');
             $diagnostic -> verbal= $request -> input('verbal');
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

    public function getListDiagnostik($id)
    {
       $query = "select id, date_format(created_at, '%d-%m-%Y') AS updated_at, date_format(created_at, '%H:%i') as jam
                from data_diagnostik
                where id_pasien = $id";

        $results = DB::select($query);
        return response()->json(['message' => 'Data Diagnostik berhasil ditemukan', 'data' => $results]);
    }
}


