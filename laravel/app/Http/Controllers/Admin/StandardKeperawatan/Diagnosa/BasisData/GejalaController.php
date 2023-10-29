<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Gejala;
use Illuminate\Support\Facades\Validator;

class GejalaController extends Controller
{
    public function index()
    {
        $gejala = Gejala::all();
        return response()->json($gejala);
    }

    public function show($id)
    {
        $gejala = Gejala::find($id);

        if ($gejala) {
            return response()->json($gejala);
        } else {
            return response()->json(['message' => 'Gejala tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'id_kategori_gejala' => 'required|int',
            'id_jenis_gejala' => 'required|int',
            'nama_gejala' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $gejala = new Gejala();
        $gejala->fill($request->all());
        $gejala->save();

        return response()->json(['message' => 'Gejala berhasil ditambahkan', 'data' => $gejala]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'id_kategori_gejala' => 'required|int',
            'id_jenis_gejala' => 'required|int',
            'nama_gejala' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $gejala = Gejala::find($id);

        if ($gejala) {
            $gejala->fill($request->all());
            $gejala->save();

            return response()->json(['message' => 'Gejala berhasil diperbarui', 'data' => $gejala]);
        } else {
            return response()->json(['message' => 'Gejala tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $gejala = Gejala::find($id);

        if ($gejala) {
            $gejala->delete();
            return response()->json(['message' => 'Gejala berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Gejala tidak ditemukan'], 404);
        }
    }
}


