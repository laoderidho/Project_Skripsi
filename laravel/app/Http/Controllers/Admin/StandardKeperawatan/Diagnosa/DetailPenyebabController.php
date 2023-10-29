<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\DetailPenyebab;
use Illuminate\Support\Facades\Validator;

class DetailPenyebabController extends Controller
{
    public function index()
    {
        $detailPenyebab = DetailPenyebab::all();
        return response()->json($detailPenyebab);
    }

    public function show($id)
    {
        $detailPenyebab = DetailPenyebab::find($id);

        if ($detailPenyebab) {
            return response()->json($detailPenyebab);
        } else {
            return response()->json(['message' => 'Detail Penyebab tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'id_jenis_penyebab' => 'required|int',
            'nama_penyebab' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detailPenyebab = new DetailPenyebab();
        $detailPenyebab->fill($request->all());
        $detailPenyebab->save();

        return response()->json(['message' => 'Detail Penyebab berhasil ditambahkan', 'data' => $detailPenyebab]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'id_jenis_penyebab' => 'required|int',
            'nama_penyebab' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $detailPenyebab = DetailPenyebab::find($id);

        if ($detailPenyebab) {
            $detailPenyebab->fill($request->all());
            $detailPenyebab->save();

            return response()->json(['message' => 'Detail Penyebab berhasil diperbarui', 'data' => $detailPenyebab]);
        } else {
            return response()->json(['message' => 'Detail Penyebab tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $detailPenyebab = DetailPenyebab::find($id);

        if ($detailPenyebab) {
            $detailPenyebab->delete();
            return response()->json(['message' => 'Detail Penyebab berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Detail Penyebab tidak ditemukan'], 404);
        }
    }
}
