<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\Pemeriksaan;
use Illuminate\Support\Facades\Validator;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $pemeriksaan = Pemeriksaan::all();
        return response()->json($pemeriksaan);
    }

    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::find($id);

        if ($pemeriksaan) {
            return response()->json($pemeriksaan);
        } else {
            return response()->json(['message' => 'Pemeriksaan tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_perawat' => 'required|int',
            'id_form_diagnosa' => 'required|int',
            'id_tindakan_intervensi' => 'required|int',
            'id_implementasi' => 'required|int',
            'id_evaluasi' => 'required|int',
            'jam_pemberian_diagnosa' => 'required|date_format:H:i',
            'jam_pemberian_gejala' => 'required|date_format:H:i',
            'jam_pemberian_implementasi' => 'required|date_format:H:i',
            'jam_penilaian_evaluasi' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pemeriksaan = new Pemeriksaan();
        $pemeriksaan->fill($request->all());
        $pemeriksaan->save();

        return response()->json(['message' => 'Pemeriksaan berhasil ditambahkan', 'data' => $pemeriksaan]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_perawat' => 'required|int',
            'id_form_diagnosa' => 'required|int',
            'id_tindakan_intervensi' => 'required|int',
            'id_implementasi' => 'required|int',
            'id_evaluasi' => 'required|int',
            'jam_pemberian_diagnosa' => 'required|date_format:H:i',
            'jam_pemberian_gejala' => 'required|date_format:H:i',
            'jam_pemberian_implementasi' => 'required|date_format:H:i',
            'jam_penilaian_evaluasi' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pemeriksaan = Pemeriksaan::find($id);

        if ($pemeriksaan) {
            $pemeriksaan->fill($request->all());
            $pemeriksaan->save();

            return response()->json(['message' => 'Pemeriksaan berhasil diperbarui', 'data' => $pemeriksaan]);
        } else {
            return response()->json(['message' => 'Pemeriksaan tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::find($id);

        if ($pemeriksaan) {
            $pemeriksaan->delete();
            return response()->json(['message' => 'Pemeriksaan berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Pemeriksaan tidak ditemukan'], 404);
        }
    }
}
