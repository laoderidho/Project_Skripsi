<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\TindakanIntervensi;
use Illuminate\Support\Facades\Validator;

class TindakanIntervensiController extends Controller
{
    public function index()
    {
        $tindakanIntervensi = TindakanIntervensi::all();
        return response()->json($tindakanIntervensi);
    }

    public function show($id)
    {
        $tindakanIntervensi = TindakanIntervensi::find($id);

        if ($tindakanIntervensi) {
            return response()->json($tindakanIntervensi);
        } else {
            return response()->json(['message' => 'Tindakan Intervensi tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori_tindakan' => 'required|int',
            'id_intervensi' => 'required|string|max:255',
            'nama_tindakan_intervensi' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tindakanIntervensi = new TindakanIntervensi();
        $tindakanIntervensi->fill($request->all());
        $tindakanIntervensi->save();

        return response()->json(['message' => 'Tindakan Intervensi berhasil ditambahkan', 'data' => $tindakanIntervensi]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori_tindakan' => 'required|int',
            'id_intervensi' => 'required|string|max:255',
            'nama_tindakan_intervensi' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $tindakanIntervensi = TindakanIntervensi::find($id);

        if ($tindakanIntervensi) {
            $tindakanIntervensi->fill($request->all());
            $tindakanIntervensi->save();

            return response()->json(['message' => 'Tindakan Intervensi berhasil diperbarui', 'data' => $tindakanIntervensi]);
        } else {
            return response()->json(['message' => 'Tindakan Intervensi tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $tindakanIntervensi = TindakanIntervensi::find($id);

        if ($tindakanIntervensi) {
            $tindakanIntervensi->delete();
            return response()->json(['message' => 'Tindakan Intervensi berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Tindakan Intervensi tidak ditemukan'], 404);
        }
    }
}
