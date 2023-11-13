<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Luaran;
use App\Models\Admin\KriteriaLuaran;

class InputLuaranController extends Controller
{

    public function read(){
        $luaran = Luaran::all();

        return response()->json([
            'message' => 'Success',
            'data' => $luaran,
        ]);
    }

    public function createLuaran(Request $request)
    {
        $request->validate([
            'kode_luaran' => 'required|string|max:255',
            'nama_luaran' => 'required|string|max:255',
            'nama_kriteria_luaran' => 'required|array',
            // 'nama_kriteria_luaran.*' => 'string|max:255',
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
    public function detailLuaran($id)
    {
        $luaran = Luaran::where('id', $id)->first();
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
    public function getLuaran()
    {
        $diagnosa = Luaran::all();

        return response()->json([
            'message' => 'Success',
            'data' => $diagnosa,
        ]);
    }
    public function update(Request $request, $id_luaran)
    {
        $request->validate([
            'nama_luaran' => 'required|string|max:255',
            'nama_kriteria_luaran' => 'required|array',
            'nama_kriteria_luaran.*' => 'string|max:255',
        ]);

        // Cari luaran berdasarkan kode_luaran
        $luaran = Luaran::where('id', $id_luaran)->first();

        // Jika luaran dengan kode_luaran tersebut tidak ditemukan, beri respon sesuai kebutuhan
        if (!$luaran) {
            return response()->json(['message' => 'Data luaran tidak ditemukan'], 404);
        }

        // Update nama_luaran berdasarkan input pengguna
        $luaran->update([
            'nama_luaran' => $request->input('nama_luaran'),
        ]);

        // Hapus kriteria luaran yang terkait dengan luaran ini
        KriteriaLuaran::where('id_luaran', $luaran->id)->delete();

        // Tambahkan kriteria luaran baru berdasarkan input pengguna
        foreach ($request->input('nama_kriteria_luaran') as $kriteria) {
            KriteriaLuaran::create([
                'id_luaran' => $luaran->id,
                'nama_kriteria_luaran' => $kriteria,
            ]);
        }

        return response()->json(['message' => 'Data luaran berhasil diperbarui']);
    }
    public function delete($kode_luaran)
    {
        // Cari luaran berdasarkan kode_luaran
        $luaran = Luaran::where('kode_luaran', $kode_luaran)->first();

        // Jika luaran dengan kode_luaran tersebut tidak ditemukan, beri respon sesuai kebutuhan
        if (!$luaran) {
            return response()->json(['message' => 'Data luaran tidak ditemukan'], 404);
        }

        // Cari dan hapus kriteria luaran yang terkait dengan luaran ini berdasarkan id luaran
        KriteriaLuaran::where('id_luaran', $luaran->id)->delete();

        // Hapus luaran
        $luaran->delete();

        return response()->json(['message' => 'Data luaran berhasil dihapus']);
    }

}
