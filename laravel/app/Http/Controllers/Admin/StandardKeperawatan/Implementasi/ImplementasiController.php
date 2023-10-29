<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Implementasi;
use Illuminate\Support\Facades\Validator;

class ImplementasiController extends Controller
{
    public function index()
    {
        $implementasi = Implementasi::all();
        return response()->json($implementasi);
    }

    public function show($id)
    {
        $implementasi = Implementasi::find($id);

        if ($implementasi) {
            return response()->json($implementasi);
        } else {
            return response()->json(['message' => 'Implementasi tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tindakan_intervensi' => 'required|string|max:255',
            'id_tindakan_implementasi' => 'required|int',
            'status_implementasi' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $implementasi = new Implementasi();
        $implementasi->fill($request->all());
        $implementasi->save();

        return response()->json(['message' => 'Implementasi berhasil ditambahkan', 'data' => $implementasi]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_tindakan_intervensi' => 'required|string|max:255',
            'id_tindakan_implementasi' => 'required|int',
            'status_implementasi' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $implementasi = Implementasi::find($id);

        if ($implementasi) {
            $implementasi->fill($request->all());
            $implementasi->save();

            return response()->json(['message' => 'Implementasi berhasil diperbarui', 'data' => $implementasi]);
        } else {
            return response()->json(['message' => 'Implementasi tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $implementasi = Implementasi::find($id);

        if ($implementasi) {
            $implementasi->delete();
            return response()->json(['message' => 'Implementasi berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Implementasi tidak ditemukan'], 404);
        }
    }
}
