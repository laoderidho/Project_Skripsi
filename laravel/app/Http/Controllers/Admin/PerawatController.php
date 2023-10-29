<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\Auth; // Import model User jika belum diimpor sebelumnya
use App\Models\Admin\WaktuShift; // Import model WaktuShift jika belum diimpor sebelumnya
use App\Models\Perawat; // Import model Perawat jika belum diimpor sebelumnya
use Illuminate\Support\Facades\Validator;

class PerawatController extends Controller
{
    public function index()
    {
        // Logika untuk menampilkan data perawat dari database
        $perawats = Perawat::all();
        return response()->json($perawats);
    }

    public function show($id)
    {
        // Logika untuk menampilkan data perawat berdasarkan ID dari database
        $perawat = Perawat::find($id);

        if ($perawat) {
            return response()->json($perawat);
        } else {
            return response()->json(['message' => 'Perawat tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|string|max:255',
            'id_waktu_shift' => 'required|int',
            'status' => 'required|boolean',
        ]);

        // Jika validasi gagal, kembalikan pesan error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat instance Perawat dengan data dari request
        $perawat = new Perawat();
        $perawat->id_user = $request->input('id_user');
        $perawat->id_waktu_shift = $request->input('id_waktu_shift');
        $perawat->status = $request->input('status');
        $perawat->save();

        return response()->json(['message' => 'Perawat berhasil ditambahkan', 'data' => $perawat]);
    }

    public function update(Request $request, $id)
    {
        // Logika untuk memperbarui data perawat berdasarkan ID
        $perawat = Perawat::find($id);

        if ($perawat) {
            $perawat->id_user = $request->input('id_user');
            $perawat->id_waktu_shift = $request->input('id_waktu_shift');
            $perawat->status = $request->input('status');
            $perawat->save();

            return response()->json(['message' => 'Data perawat berhasil diperbarui', 'data' => $perawat]);
        } else {
            return response()->json(['message' => 'Perawat tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        // Logika untuk menghapus data perawat berdasarkan ID
        $perawat = Perawat::find($id);

        if ($perawat) {
            $perawat->delete();
            return response()->json(['message' => 'Perawat berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Perawat tidak ditemukan'], 404);
        }
    }
}
