<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\Perawatan;
use Illuminate\Support\Facades\Validator;

class PerawatanController extends Controller
{
    public function index()
    {
        $perawatan = Perawatan::all();
        return response()->json($perawatan);
    }

    public function show($id)
    {
        $perawatan = Perawatan::find($id);

        if ($perawatan) {
            return response()->json($perawatan);
        } else {
            return response()->json(['message' => 'Perawatan tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_perawat' => 'required|int',
            'id_data_diagnostik' => 'required|int',
            'bed' => 'required|string|max:3',
            'waktu_pencatatan' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $perawatan = new Perawatan();
        $perawatan->fill($request->all());
        $perawatan->save();

        return response()->json(['message' => 'Perawatan berhasil ditambahkan', 'data' => $perawatan]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_perawat' => 'required|int',
            'id_data_diagnostik' => 'required|int',
            'bed' => 'required|string|max:3',
            'waktu_pencatatan' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $perawatan = Perawatan::find($id);

        if ($perawatan) {
            $perawatan->fill($request->all());
            $perawatan->save();

            return response()->json(['message' => 'Perawatan berhasil diperbarui', 'data' => $perawatan]);
        } else {
            return response()->json(['message' => 'Perawatan tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $perawatan = Perawatan::find($id);

        if ($perawatan) {
            $perawatan->delete();
            return response()->json(['message' => 'Perawatan berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Perawatan tidak ditemukan'], 404);
        }
    }
}
