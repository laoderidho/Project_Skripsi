<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Admin\KriteriaLuaran;
use App\Models\Admin\Luaran;
use Illuminate\Http\Request;
use App\Models\Perawat\Pemeriksaan;
// validator suport
use Illuminate\Support\Facades\Validator;

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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 400);
        }

        $nama_luaran = $request->nama_luaran;
    }
}
