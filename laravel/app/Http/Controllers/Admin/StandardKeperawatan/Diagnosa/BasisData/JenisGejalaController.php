<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\JenisGejala;
use Illuminate\Support\Facades\Validator;

class JenisGejalaController extends Controller
{
    public function index()
    {
        $jenisGejala = JenisGejala::all();
        return response()->json($jenisGejala);
    }

    public function show($id)
    {
        $jenisGejala = JenisGejala::find($id);

        if ($jenisGejala) {
            return response()->json($jenisGejala);
        } else {
            return response()->json(['message' => 'Jenis Gejala tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis_gejala' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jenisGejala = new JenisGejala();
        $jenisGejala->fill($request->all());
        $jenisGejala->save();

        return response()->json(['message' => 'Jenis Gejala berhasil ditambahkan', 'data' => $jenisGejala]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis_gejala' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jenisGejala = JenisGejala::find($id);

        if ($jenisGejala) {
            $jenisGejala->fill($request->all());
            $jenisGejala->save();

            return response()->json(['message' => 'Jenis Gejala berhasil diperbarui', 'data' => $jenisGejala]);
        } else {
            return response()->json(['message' => 'Jenis Gejala tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $jenisGejala = JenisGejala::find($id);

        if ($jenisGejala) {
            $jenisGejala->delete();
            return response()->json(['message' => 'Jenis Gejala berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Jenis Gejala tidak ditemukan'], 404);
        }
    }
}
