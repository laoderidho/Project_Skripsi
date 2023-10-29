<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\KategoriGejala;
use Illuminate\Support\Facades\Validator;

class KategoriGejalaController extends Controller
{
    public function index()
    {
        $kategoriGejala = KategoriGejala::all();
        return response()->json($kategoriGejala);
    }

    public function show($id)
    {
        $kategoriGejala = KategoriGejala::find($id);

        if ($kategoriGejala) {
            return response()->json($kategoriGejala);
        } else {
            return response()->json(['message' => 'Kategori Gejala tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori_gejala' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kategoriGejala = new KategoriGejala();
        $kategoriGejala->fill($request->all());
        $kategoriGejala->save();

        return response()->json(['message' => 'Kategori Gejala berhasil ditambahkan', 'data' => $kategoriGejala]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori_gejala' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kategoriGejala = KategoriGejala::find($id);

        if ($kategoriGejala) {
            $kategoriGejala->fill($request->all());
            $kategoriGejala->save();

            return response()->json(['message' => 'Kategori Gejala berhasil diperbarui', 'data' => $kategoriGejala]);
        } else {
            return response()->json(['message' => 'Kategori Gejala tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $kategoriGejala = KategoriGejala::find($id);

        if ($kategoriGejala) {
            $kategoriGejala->delete();
            return response()->json(['message' => 'Kategori Gejala berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Kategori Gejala tidak ditemukan'], 404);
        }
    }
}
