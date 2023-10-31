<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\PenilaianKriteria;
use Illuminate\Support\Facades\Validator;

class PenilaianKriteriaController extends Controller
{
    public function index()
    {
        $penilaianKriteria = PenilaianKriteria::all();
        return response()->json($penilaianKriteria);
    }

    public function show($id)
    {
        $penilaianKriteria = PenilaianKriteria::find($id);

        if ($penilaianKriteria) {
            return response()->json($penilaianKriteria);
        } else {
            return response()->json(['message' => 'Penilaian Kriteria tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kriteria_luaran' => 'required|int',
            'nilai' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $penilaianKriteria = new PenilaianKriteria();
        $penilaianKriteria->fill($request->all());
        $penilaianKriteria->save();

        return response()->json(['message' => 'Penilaian Kriteria berhasil ditambahkan', 'data' => $penilaianKriteria]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kriteria_luaran' => 'required|int',
            'nilai' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $penilaianKriteria = PenilaianKriteria::find($id);

        if ($penilaianKriteria) {
            $penilaianKriteria->fill($request->all());
            $penilaianKriteria->save();

            return response()->json(['message' => 'Penilaian Kriteria berhasil diperbarui', 'data' => $penilaianKriteria]);
        } else {
            return response()->json(['message' => 'Penilaian Kriteria tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $penilaianKriteria = PenilaianKriteria::find($id);

        if ($penilaianKriteria) {
            $penilaianKriteria->delete();
            return response()->json(['message' => 'Penilaian Kriteria berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Penilaian Kriteria tidak ditemukan'], 404);
        }
    }
}
