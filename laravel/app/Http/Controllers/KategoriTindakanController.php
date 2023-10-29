<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\KategoriTindakan;
use Illuminate\Support\Facades\Validator;

class KategoriTindakanController extends Controller
{
    public function index()
    {
        $kategoriTindakan = KategoriTindakan::all();
        return response()->json($kategoriTindakan);
    }

    public function show($id)
    {
        $kategoriTindakan = KategoriTindakan::find($id);

        if ($kategoriTindakan) {
            return response()->json($kategoriTindakan);
        } else {
            return response()->json(['message' => 'Kategori Tindakan tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori_tindakan' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kategoriTindakan = new KategoriTindakan();
        $kategoriTindakan->fill($request->all());
        $kategoriTindakan->save();

        return response()->json(['message' => 'Kategori Tindakan berhasil ditambahkan', 'data' => $kategoriTindakan]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori_tindakan' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kategoriTindakan = KategoriTindakan::find($id);

        if ($kategoriTindakan) {
            $kategoriTindakan->fill($request->all());
            $kategoriTindakan->save();

            return response()->json(['message' => 'Kategori Tindakan berhasil diperbarui', 'data' => $kategoriTindakan]);
        } else {
            return response()->json(['message' => 'Kategori Tindakan tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $kategoriTindakan = KategoriTindakan::find($id);

        if ($kategoriTindakan) {
            $kategoriTindakan->delete();
            return response()->json(['message' => 'Kategori Tindakan berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Kategori Tindakan tidak ditemukan'], 404);
        }
    }
}
