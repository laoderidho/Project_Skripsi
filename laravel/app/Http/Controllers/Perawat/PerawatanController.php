<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\Perawatan;
use App\Models\Perawat\RawatInap;
use App\Models\Admin\Bed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PerawatanController extends Controller
{
    public function Add(Request $request, $id_pasien)
    {
        $validator = Validator::make($request->all(), [
            'bed' => 'required||string',
            'triase' => 'required|string|in:merah,kuning,hijau,hitam'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $perawatan = new Perawatan;
        $perawatan->id_pasien = $id_pasien;
        $perawatan->bed = $request->bed;
        $perawatan->waktu_pencatatan = Carbon::now();
        $perawatan->status_pasien = "sakit";
        $perawatan->save();

        $bed = Bed::where('no_bed', $request->bed)->first();
        $bed->status = 1;
        $bed->update();

        return response()->json([
            "message" => "Berhasil menambahkan data perawatan",
            "data" => $perawatan
        ], 201);
    }
    public function RiwayatPerawatan(Request $request, $id_pasien){
        $perawatan = Perawatan::find($id_pasien);
        if($perawatan){
            return response()->json($perawatan);
        } else {
            return response()->json(['message' => 'Riwayat tidak ditemukan'], 404);
        }
    }
}
