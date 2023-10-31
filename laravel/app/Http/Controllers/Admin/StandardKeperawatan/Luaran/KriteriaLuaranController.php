<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\KriteriaLuaran;
use Illuminate\Support\Facades\Validator;

class KriteriaLuaranController extends Controller
{
    public function index()
    {
        $kriteriaLuaran = KriteriaLuaran::all();
        return response()->json($kriteriaLuaran);
    }

    public function show($id)
    {
        $kriteriaLuaran = KriteriaLuaran::find($id);

        if ($kriteriaLuaran) {
            return response()->json($kriteriaLuaran);
        } else {
            return response()->json(['message' => 'Kriteria Luaran tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_luaran' => 'required|int',
            'nama_kriteria_luaran' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kriteriaLuaran = new KriteriaLuaran();
        $kriteriaLuaran->fill($request->all());
        $kriteriaLuaran->save();

        return response()->json(['message' => 'Kriteria Luaran berhasil ditambahkan', 'data' => $kriteriaLuaran]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_luaran' => 'required|int',
            'nama_kriteria_luaran' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kriteriaLuaran = KriteriaLuaran::find($id);

        if ($kriteriaLuaran) {
            $kriteriaLuaran->fill($request->all());
            $kriteriaLuaran->save();

            return response()->json(['message' => 'Kriteria Luaran berhasil diperbarui', 'data' => $kriteriaLuaran]);
        } else {
            return response()->json(['message' => 'Kriteria Luaran tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $kriteriaLuaran = KriteriaLuaran::find($id);

        if ($kriteriaLuaran) {
            $kriteriaLuaran->delete();
            return response()->json(['message' => 'Kriteria Luaran berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Kriteria Luaran tidak ditemukan'], 404);
        }
    }
}
