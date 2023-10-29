<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\Evaluasi;
use Illuminate\Support\Facades\Validator;

class EvaluasiController extends Controller
{
    public function index()
    {
        $evaluasi = Evaluasi::all();
        return response()->json($evaluasi);
    }

    public function show($id)
    {
        $evaluasi = Evaluasi::find($id);

        if ($evaluasi) {
            return response()->json($evaluasi);
        } else {
            return response()->json(['message' => 'Evaluasi tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_luaran' => 'required|int',
            'id_penilaian_kriteria' => 'required|int',
            'subjektif' => 'required|string|max:255',
            'objektif' => 'required|string|max:255',
            'pencapaian' => 'required|string|max:255',
            'perencanaan' => 'required|string|max:255',
            'catatan_evaluasi' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $evaluasi = new Evaluasi();
        $evaluasi->fill($request->all());
        $evaluasi->save();

        return response()->json(['message' => 'Evaluasi berhasil ditambahkan', 'data' => $evaluasi]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_luaran' => 'required|int',
            'id_penilaian_kriteria' => 'required|int',
            'subjektif' => 'required|string|max:255',
            'objektif' => 'required|string|max:255',
            'pencapaian' => 'required|string|max:255',
            'perencanaan' => 'required|string|max:255',
            'catatan_evaluasi' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $evaluasi = Evaluasi::find($id);

        if ($evaluasi) {
            $evaluasi->fill($request->all());
            $evaluasi->save();

            return response()->json(['message' => 'Evaluasi berhasil diperbarui', 'data' => $evaluasi]);
        } else {
            return response()->json(['message' => 'Evaluasi tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $evaluasi = Evaluasi::find($id);

        if ($evaluasi) {
            $evaluasi->delete();
            return response()->json(['message' => 'Evaluasi berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Evaluasi tidak ditemukan'], 404);
        }
    }
}
