<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\DeclensionController;
use Illuminate\Http\Request;
use App\Models\Perawat\Pemeriksaan;
use App\Models\Perawat\StandarForm\Form_Intervensi;
use App\Models\Perawat\StandarForm\Form_Implementasi;
use App\Models\Admin\Declension;
use App\Models\Admin\Perawat;
use App\Models\Perawat\StandarForm\Form_Evaluasi;
use Carbon\Carbon;
// auth suport
use Illuminate\Support\Facades\Auth;
// validator suport
use Illuminate\Support\Facades\Validator;
// db suport
use Illuminate\Support\Facades\DB;

class InputImplementasiController extends Controller
{
    public function getIndex($id){
        $implementasi = Form_Implementasi::where('id_pemeriksaan',$id)->get();
        return response()->json([
            'message' => 'Sukses',
            'data' => $implementasi,
        ]);
    }

    public function isDone($id){
        $implementasi = Form_Implementasi::where('id_pemeriksaan', $id)->where('tindakan_implementasi','!=',1)->get();
        if($implementasi->isEmpty()){
            return response()->json([
                'message' => 'Semua implementasi telah dilakukan'
            ]);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $implementasi
        ]);
    }



    public function checkList(Request $request,$id){
        $implementasi = Form_Implementasi::where('id',$id)->first();

        $validator = Validator::make($request->all(), [
            'tindakan_implementasi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 400);
        }

        if($request->tindakan_implementasi == 1){
            $implementasi->jam_ceklis = Carbon::now()->format('H:i:s');
        }else{
            $implementasi->jam_ceklis=null;
        }
        $implementasi->tindakan_implementasi = $request->tindakan_implementasi;
        $implementasi->update();

        return response()->json([
            'message' => 'Success',
            'data' =>$implementasi
        ]);
    }


    public function getImplementasiPasien($id_pemeriksaan)
    {
        $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

        if ($pemeriksaan == null) {
            return response()->json([
                'message' => 'Pemeriksaan tidak ditemukan',
            ], 404);
        }

        $form_implementasi = Form_Implementasi::where('id_pemeriksaan', $id_pemeriksaan)
            ->whereNull('jam_ceklis')
            ->get();

        if ($form_implementasi->isEmpty()) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $form_implementasi,
        ]);
    }
}
