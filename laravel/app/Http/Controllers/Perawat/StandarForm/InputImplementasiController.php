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

    public function checkList(Request $request){
        dd($request->all());
        // $users = Auth::user()->id;
        // $perawat = Perawat::where('id_user',$users)->first();
        // $perawat = $perawat->id;

        // $validator = Validator::make($request->all(),[
        //     'id'=>'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors()->toJson(), 400);
        // }
        // $id_implementasi = $request->id;
        // $id_implementasi = explode(',', $id_implementasi);

        // dd($id_implementasi);
        // transaction db
        // DB::beginTransaction();
        // try{

        // }
        // catch (\Exception $e)
        // {
        //     DB::rollback();
        //     return response()->json([
        //     'message' => 'Failed',
        //     'error' => $e->getMessage(), ],
        //     500); }
    }

        public function getImplementasiPasien($id_pemeriksaan){

            $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

            if ($pemeriksaan == null) {
                return response()->json([
                    'message' => 'Pemeriksaan tidak ditemukan',
                ], 404);
            }

            // select

            $form_evaluasi = Form_Implementasi::where('id_pemeriksaan', $id_pemeriksaan)->whereNull('jam_ceklis')->get();

            if ($form_evaluasi->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'Success',
                'data' => $form_evaluasi,
            ]);
        }
}

