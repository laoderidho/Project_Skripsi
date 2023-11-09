<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\DataDiagnostik;
use Illuminate\Support\Facades\Validator;

class DataDiagnostikController extends Controller
{
    public function index()
    {
        $dataDiagnostik = DataDiagnostik::all();
        return response()->json($dataDiagnostik);
    }

    public function show($id)
    {
        $dataDiagnostik = DataDiagnostik::find($id);

        if ($dataDiagnostik) {
            return response()->json($dataDiagnostik);
        } else {
            return response()->json(['message' => 'Data Diagnostik tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pasien' => 'required|int',
            'keluhan_utama' => 'required|string|max:255',
            'riwayat_penyakit' => 'required|string|max:255',
            'riwayat_alergi' => 'required|string|max:255',
            'risiko_jatuh' => 'required|string|max:255',
            'risiko_nyeri' => 'required|string|max:255',
            'suhu' => 'required|numeric',
            'tekanan_darah' => 'required|string|max:255',
            'nadi' => 'required|string|max:255',
            'laju_respirasi' => 'required|string|max:255',
            'kesadaran' => 'required|string|max:255',
            'gcs_eyes' => 'required|numeric',
            'gcs_motoric' => 'required|numeric',
            'gcs_visual' => 'required|numeric',
            'pemeriksaan_fisik' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dataDiagnostik = new DataDiagnostik();
        $dataDiagnostik->fill($request->all());
        $dataDiagnostik->save();

        return response()->json(['message' => 'Data Diagnostik berhasil ditambahkan', 'data' => $dataDiagnostik]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_pasien' => 'required|int',
            'keluhan_utama' => 'required|string|max:255',
            'riwayat_penyakit' => 'required|string|max:255',
            'riwayat_alergi' => 'required|string|max:255',
            'risiko_jatuh' => 'required|string|max:255',
            'risiko_nyeri' => 'required|string|max:255',
            'suhu' => 'required|numeric',
            'tekanan_darah' => 'required|string|max:255',
            'nadi' => 'required|string|max:255',
            'laju_respirasi' => 'required|string|max:255',
            'kesadaran' => 'required|string|max:255',
            'gcs_eyes' => 'required|numeric',
            'gcs_motoric' => 'required|numeric',
            'gcs_visual' => 'required|numeric',
            'pemeriksaan_fisik' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dataDiagnostik = DataDiagnostik::find($id);

        if ($dataDiagnostik) {
            $dataDiagnostik->fill($request->all());
            $dataDiagnostik->save();

            return response()->json(['message' => 'Data Diagnostik berhasil diperbarui', 'data' => $dataDiagnostik]);
        } else {
            return response()->json(['message' => 'Data Diagnostik tidak ditemukan'], 404);
        }
    }

}
