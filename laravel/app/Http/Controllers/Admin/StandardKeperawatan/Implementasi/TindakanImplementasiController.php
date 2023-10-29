<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\TindakanImplementasi;
use Illuminate\Support\Facades\Validator;

class TindakanImplementasiController extends Controller
{
    public function index()
    {
        $tindakanImplementasi = TindakanImplementasi::all();
        return response()->json($tindakanImplementasi);
    }

    public function show($id)
    {
        $tindakanImplementasi = TindakanImplementasi::find($id);

        if ($tindakanImplementasi) {
            return response()->json($tindakanImplementasi);
        } else {
            return response()->json(['message' => 'Tindakan Implementasi tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tindakan_intervensi' => 'required|int',
            'kalimat_implementasi' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tindakanImplementasi = new TindakanImplementasi();
        $tindakanImplementasi->fill($request->all());
        $tindakanImplementasi->save();

        return response()->json(['message' => 'Tindakan Implementasi berhasil ditambahkan', 'data' => $tindakanImplementasi]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_tindakan_intervensi' => 'required|int',
            'kalimat_implementasi' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tindakanImplementasi = TindakanImplementasi::find($id);

        if ($tindakanImplementasi) {
            $tindakanImplementasi->fill($request->all());
            $tindakanImplementasi->save();

            return response()->json(['message' => 'Tindakan Implementasi berhasil diperbarui', 'data' => $tindakanImplementasi]);
        } else {
            return response()->json(['message' => 'Tindakan Implementasi tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $tindakanImplementasi = TindakanImplementasi::find($id);

        if ($tindakanImplementasi) {
            $tindakanImplementasi->delete();
            return response()->json(['message' => 'Tindakan Implementasi berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Tindakan Implementasi tidak ditemukan'], 404);
        }
    }
}
