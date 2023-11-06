<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Luaran;
use App\Models\Admin\KriteriaLuaran;

class InputLuaranController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'kode_luaran' => 'required|string|max:255',
            'nama_luaran' => 'required|string|max:255',
            'nama_kriteria_luaran' => 'required|array',
            'nama_kriteria_luaran.*' => 'string|max:255',
        ]);

        // Cari luaran berdasarkan kode_luaran
        $luaran = Luaran::create([
            'kode_luaran' => $request->input('kode_luaran'),
            'nama_luaran' => $request->input('nama_luaran'),
        ]);

        // Jika luaran gagal disimpan, beri respon sesuai kebutuhan
        if (!$luaran) {
            return response()->json(['message' => 'Gagal menyimpan luaran'], 500);
        }

        // Buat kriteria luaran untuk luaran yang ditemukan
        foreach ($request->input('nama_kriteria_luaran') as $kriteria) {
            KriteriaLuaran::create([
                'id_luaran' => $luaran->id,
                'nama_kriteria_luaran' => $kriteria,
            ]);
        }

        return response()->json(['message' => 'Kriteria luaran berhasil ditambahkan']);
    }
    public function read($kode_luaran)
    {
        $luaran = Luaran::where('kode_luaran', $kode_luaran)->first();
        // dd($luaran);
        if (!$luaran) {
            return response()->json(['message' => 'Data luaran tidak ditemukan'], 404);
        }
        $kriteriaLuaran = KriteriaLuaran::where('id_luaran', $luaran->id)->pluck('nama_kriteria_luaran')->toArray();
        // dd($kriteriaLuaran);
        $result = [
            'kode_luaran' => $luaran->kode_luaran,
            'nama_luaran' => $luaran->nama_luaran,
            'nama_kriteria_luaran' => $kriteriaLuaran,
        ];

        return response()->json(['data' => $result]);
    }

    public function update(Request $request, $kode_luaran)
    {
        $request->validate([
            'nama_luaran' => 'required|string|max:255',
            'nama_kriteria_luaran' => 'required|array',
            'nama_kriteria_luaran.*' => 'string|max:255',
        ]);

        $luaran = Luaran::where('kode_luaran', $kode_luaran)->first();

        if (!$luaran) {
            return response()->json(['message' => 'Data luaran tidak ditemukan'], 404);
        }

        $luaran->update([
            'nama_luaran' => $request->input('nama_luaran'),
        ]);

        KriteriaLuaran::where('kode_luaran', $kode_luaran)->delete();

        foreach ($request->input('nama_kriteria_luaran') as $kriteria) {
            KriteriaLuaran::create([
                'kode_luaran' => $luaran->kode_luaran,
                'kriteria' => $kriteria,
            ]);
        }

        return response()->json(['message' => 'Data luaran berhasil diperbarui']);
    }

    public function delete($kode_luaran)
    {
        $luaran = Luaran::where('kode_luaran', $kode_luaran)->first();

        if (!$luaran) {
            return response()->json(['message' => 'Data luaran tidak ditemukan'], 404);
        }

        KriteriaLuaran::where('kode_luaran', $kode_luaran)->delete();
        $luaran->delete();

        return response()->json(['message' => 'Data luaran berhasil dihapus']);
    }
}
