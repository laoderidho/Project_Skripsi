<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\Perawatan;
use App\Models\Perawat\RawatInap;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PerawatanController extends Controller
{
    public function Add(Request $request, $id_pasien, $id_data_diagnostik)
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
        $perawatan->id_data_diagnostik = $id_data_diagnostik;
        $perawatan->bed = $request->bed;
        $perawatan->waktu_pencatatan = Carbon::now();
        $perawatan->status_pasien = "sakit";
        $perawatan->save();

        $rawat_inap = new RawatInap;
        $rawat_inap->id_pasien = $id_pasien;
        $rawat_inap->triase = request('triase');
        $rawat_inap->status = "rawat inap";
        $rawat_inap->save();


        return response()->json([
            "message" => "Berhasil menambahkan data perawatan",
            "data" => $perawatan
        ], 201);
    }
}
