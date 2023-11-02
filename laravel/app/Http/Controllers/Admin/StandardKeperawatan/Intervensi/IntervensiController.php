<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Intervensi;
use Illuminate\Support\Facades\Validator;

class IntervensiController extends Controller
{
    public function index()
    {
        $intervensis = Intervensi::all();
        return response()->json($intervensis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_intervensi' => 'required|string|max:255',
            // Tambahkan validasi untuk field lain jika diperlukan
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $intervensi = new Intervensi();
        $intervensi->fill($request->all());
        $intervensi->save();

        return response()->json(['message' => 'Intervensi telah berhasil ditambahkan']);
    }

    public function show($id)
    {
        $intervensi = Intervensi::find($id);
        if ($intervensi) {
            return response()->json($intervensi);
        } else {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $intervensi = Intervensi::find($id);
        if ($intervensi) {
            $intervensi->id_intervensi = $request->input('id_intervensi');
            $intervensi->nama_intervensi = $request->input('nama_intervensi');
            // Update field lain sesuai kebutuhan

            $intervensi->save();

            return response()->json(['message' => 'Data intervensi berhasil diperbarui', 'data' => $intervensi]);
        } else {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $intervensi = Intervensi::find($id);
        if ($intervensi) {
            $intervensi->delete();
            return response()->json(['message' => 'Intervensi telah berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }
    }
}
