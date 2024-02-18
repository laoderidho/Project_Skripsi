<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perawat\Pemeriksaan;
use App\Models\Perawat\StandarForm\Form_Evaluasi;
// validator
use Illuminate\Support\Facades\Validator;
// suport db
use Illuminate\Support\Facades\DB;
// carbon
use Carbon\Carbon;

use App\Models\Perawat\StandarForm\Hasil_Evaluasi;

class EvaluasiController extends Controller
{

    public function PenilaianLuaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'hasil_luaran' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 400);
        }

        $id_luaran = explode(',', $request->id);
        $hasil_luaran = explode(',', $request->hasil_luaran);

        for ($i = 0; $i < count($id_luaran); $i++) {
            $luaran = Form_Evaluasi::find($id_luaran[$i]);

            if ($luaran == null) {
                return response()->json([
                    'message' => 'Luaran tidak ditemukan',
                ], 404);
            }

            $luaran->hasil_luaran = $hasil_luaran[$i];
            $luaran->update();
        }

        return response()->json([
            'message' => 'Success',
        ]);
    }

    public function getLuaran($id_pemeriksaan)
    {

        $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

        if ($pemeriksaan == null) {
            return response()->json([
                'message' => 'Pemeriksaan tidak ditemukan',
            ], 404);
        }

        $luaran = Form_Evaluasi::where('id_pemeriksaan', $id_pemeriksaan)->whereNull('hasil_luaran')->get();

        if ($luaran->isEmpty()) {
            return response()->json([
                'message' => 'Luaran tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Success',
            'data' => $luaran,
        ]);
    }

    public function resultEvaluasi(Request $request, $id_pemeriksaan)
    {

        $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

        if ($pemeriksaan == null) {
            return response()->json([
                'message' => 'Pemeriksaan tidak ditemukan',
            ], 404);
        }

        $pemeriksaan->jam_pemberian_evaluasi = Carbon::now();
        $pemeriksaan->update();


        $validator = Validator::make($request->all(), [
            'subjektif' => 'nullable|string',
            'objektif' => 'nullable|string',
            'perencanaan' => 'required|string',
            'catatan_lainnya' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'data' => $validator->errors(),
            ], 400);
        }

        $form_evaluasi = new Hasil_Evaluasi();

        $form_evaluasi->id_pemeriksaan = $id_pemeriksaan;
        $form_evaluasi->subjektif = $request->subjektif;
        $form_evaluasi->objektif = $request->objektif;
        $form_evaluasi->perencanaan = $request->perencanaan;
        $form_evaluasi->catatan_lainnya = $request->catatan_lainnya;
        $form_evaluasi->save();

        return response()->json([
            'message' => 'Success',
        ]);
    }
}
