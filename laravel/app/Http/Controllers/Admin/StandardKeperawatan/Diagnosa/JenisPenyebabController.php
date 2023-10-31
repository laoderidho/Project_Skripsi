<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\JenisPenyebab;
use Illuminate\Support\Facades\Validator;

class JenisPenyebabController extends Controller
{

    public function index()
    {
        $jenis_penyebab = JenisPenyebab::all();
        return response()->json($jenis_penyebab);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jenis_penyebab' => 'required|int|max:1',
            'nama_jenis_penyebab' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jenisPenyebab = new JenisPenyebab();
        $jenisPenyebab->id_jenis_penyebab = $request->input('id_jenis_penyebab');
        $jenisPenyebab->nama_jenis_penyebab = $request->input('nama_jenis_penyebab');
        $jenisPenyebab->save();

        return response()->json(['message' => 'Jenis penyebab telah berhasil ditambahkan', 'data' => $jenisPenyebab]);
    }

    public function show(string $id)
    {
        $jenisPenyebab = JenisPenyebab::find($id);
        if ($jenisPenyebab) {
            return response()->json($jenisPenyebab);
        } else {
            return response()->json(['message' => 'Jenis penyebab tidak ditemukan'], 404);
        }
    }

    public function edit(string $id)
    {
        // Mengembalikan data jenis penyebab berdasarkan ID untuk diedit pada form.
        $jenisPenyebab = JenisPenyebab::find($id);
        if ($jenisPenyebab) {
            return response()->json($jenisPenyebab);
        } else {
            return response()->json(['message' => 'Jenis penyebab tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis_penyebab' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $jenisPenyebab = JenisPenyebab::find($id);
        if ($jenisPenyebab) {
            $jenisPenyebab->nama_jenis_penyebab = $request->input('nama_jenis_penyebab');
            $jenisPenyebab->save();
            return response()->json(['message' => 'Data jenis penyebab berhasil diperbarui', 'data' => $jenisPenyebab]);
        } else {
            return response()->json(['message' => 'Jenis penyebab tidak ditemukan'], 404);
        }
    }

    public function destroy(string $id)
    {
        $jenisPenyebab = JenisPenyebab::find($id);
        if ($jenisPenyebab) {
            $jenisPenyebab->delete();
            return response()->json(['message' => 'Jenis penyebab telah berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Jenis penyebab tidak ditemukan'], 404);
        }
    }
}
