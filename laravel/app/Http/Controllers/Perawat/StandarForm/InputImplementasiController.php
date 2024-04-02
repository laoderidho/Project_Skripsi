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

    public function isDone($id_pemeriksaan){
        $implementasi = "select i.id, ti.nama_tindakan_intervensi as nama_implementasi, kt.nama_kategori_tindakan, i.jam_ceklis
                                from form_implementasi i
                                join tindakan_intervensi ti
                                on i.nama_implementasi = ti.id
                                join kategori_tindakan kt
                                on ti.id_kategori_tindakan = kt.id
                                where i.id_pemeriksaan = $id_pemeriksaan and i.tindakan_implementasi = 1";

        $implementasi = DB::select($implementasi);

        if (empty($implementasi)) {
            return response()->json([
                'message' => 'data belum di ceklis'
            ], 400);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $implementasi
        ]);
    }

    public function checkList(Request $request, $id_pemeriksaan)
    {

        $validator = Validator::make($request->all(), [
            'tindakan_implementasi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 400);
        }

        db::beginTransaction();


        $tindakan_implementasi = explode(',', $request->tindakan_implementasi);

        try{
            for($i = 0; $i < count($tindakan_implementasi); $i++){
                $id = $tindakan_implementasi[$i];
                $id = intval($id);
                $implementasi = Form_Implementasi::where('id', $id)->first();
                $implementasi->jam_ceklis = Carbon::now();
                $implementasi->tindakan_implementasi = 1;
                $implementasi->update();
            }

            db::commit();
        }catch(\Exception $e){
            db::rollback();
            return response()->json([
                'message' => 'Gagal',
                'data' => $e->getMessage(),
            ], 400);
        }

        $id_pemeriksaan = intval($id_pemeriksaan);

        $implementasi = $this->getDataImplementasi($id_pemeriksaan);
        if ($implementasi == null) {
            $form_pemeriksaan = Pemeriksaan::find($id_pemeriksaan);
            $form_pemeriksaan->jam_pemberian_implementasi = Carbon::now();
            $form_pemeriksaan->update();
        }

        return response()->json([
            'message' => 'Success',
        ]);
    }

    private function getDataImplementasi($id){
        $data = "select i.id, ti.nama_tindakan_intervensi as nama_implementasi, kt.nama_kategori_tindakan
                                from form_implementasi i
                                join tindakan_intervensi ti
                                on i.nama_implementasi = ti.id
                                join kategori_tindakan kt
                                on ti.id_kategori_tindakan = kt.id
                                where i.id_pemeriksaan = $id and i.tindakan_implementasi = 0";

        $data = DB::select($data);


        return $data;

    }

    public function getImplementasiPasien($id_pemeriksaan)
    {
        $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

        if ($pemeriksaan == null) {
            return response()->json([
                'message' => 'Pemeriksaan tidak ditemukan',
            ], 404);
        }

        $form_implementasi = "select i.id, ti.nama_tindakan_intervensi as nama_implementasi, kt.nama_kategori_tindakan
                                from form_implementasi i
                                join tindakan_intervensi ti
                                on i.nama_implementasi = ti.id
                                join kategori_tindakan kt
                                on ti.id_kategori_tindakan = kt.id
                                where i.id_pemeriksaan = $id_pemeriksaan and i.tindakan_implementasi = 0";

        $form_implementasi = DB::select($form_implementasi);

        if (empty($form_implementasi)) {
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
