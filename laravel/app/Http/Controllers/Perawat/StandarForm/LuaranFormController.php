<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Admin\KriteriaLuaran;
use App\Models\Admin\Luaran;
use Illuminate\Http\Request;
use App\Models\Perawat\Pemeriksaan;
// validator suport
use Illuminate\Support\Facades\Validator;
// db support
use Illuminate\Support\Facades\DB;
use App\Models\Perawat\StandarForm\Form_Evaluasi;
use Carbon\Carbon;

class LuaranFormController extends Controller
{
    public function getLuaran()
    {
        $luaran = Luaran::all();

        return response()->json([
            'message' => 'Success',
            'data' => $luaran,
        ]);
    }

    public function validationLuaranAttribute($id)
    {
        $luaran = Luaran::find($id);

        if ($luaran == null) {
            return response()->json([
                'message' => 'Luaran tidak ditemukan',
            ], 404);
        }

        $kriteria_luaran = KriteriaLuaran::where('id_luaran', $luaran->id)->get();

        return response()->json([
            'message' => 'Success',
            'luaran' => $luaran,
            'kriteria_luaran' => $kriteria_luaran,
        ]);
    }

    public function add(Request $request, $id_pemeriksaan)
    {
        $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

        if ($pemeriksaan == null) {
            return response()->json([
                'message' => 'Pemeriksaan tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_luaran' => 'required|string',
            'catatan_luaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 400);
        }
        $pemeriksaan->jam_penilaian_luaran = Carbon::now();
        $pemeriksaan->catatan_luaran = $request->catatan_luaran;
        $pemeriksaan->update();

        $nama_luaran = explode(',', $request->nama_luaran);


       DB::beginTransaction();

       try{
            foreach ($nama_luaran as $nl) {
                $luaran = new Form_Evaluasi();
                $luaran->id_pemeriksaan = $pemeriksaan->id;
                $luaran->nama_luaran = $nl;
                $luaran->save();
            }

            db::commit();

            return response()->json([
                'message' => 'Berhasil menambahkan luaran',
            ]);
       }catch(\Exception $e){
           DB::rollBack();
           return response()->json([
               'message' => 'Gagal menambahkan luaran',
               'errors' => $e->getMessage(),
           ], 500);
       }

    }
}
