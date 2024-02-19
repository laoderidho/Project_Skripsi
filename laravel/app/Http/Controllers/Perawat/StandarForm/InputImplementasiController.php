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
// auth suport
use Illuminate\Support\Facades\Auth;
// validator suport
use Illuminate\Support\Facades\Validator;
// db suport
use Illuminate\Support\Facades\DB;
class InputImplementasiController extends Controller
{
    public function getIndex($id){
        $implementasi = Form_Implementasi::where('id',$id)->first();
        return response()->json([
            'message' => 'Sukses',
            'data' => $implementasi,
        ]);
    }

    public function checkList(Request $request, $id){
        $users = Auth::user()->id;
        $perawat = Perawat::where('id_user',$users)->first();
        $perawat = $perawat->id;

        $validator = Validator::make($request->all(),[
            'tindakan_implementasi'=>'required|in:0,1',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        // transaction db
        DB::beginTransaction();
        try{
            $form_implementasi = Form_Implementasi::where('id',$id)->first();
            $form_implementasi->tindakan_implementasi = $request->input('tindakan_implementasi');
            $pemeriksaan = Pemeriksaan::where('id',$form_implementasi->id_pemeriksaan)->first();
            $form_implementasi->id_pemeriksaan = $pemeriksaan->id; // set the correct id_pemeriksaan value
            if($form_implementasi->tindakan_implementasi==1){
                $pemeriksaan->jam_pemberian_implementasi = date('H:i:s');
                $form_implementasi->jam_ceklis = date('H:i:s');
                $pemeriksaan->update();
            }
            $form_implementasi->update();
            DB::commit();
            return response()->json([
                'message' => 'Success',
                'data' => $form_implementasi]);

            }
            catch (\Exception $e)
            {
            DB::rollback();
            return response()->json([
            'message' => 'Failed',
            'error' => $e->getMessage(), ],
            500); }
        }
    }

