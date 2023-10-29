<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\FaktorResiko;
use Illuminate\Support\Facades\Validator;

class FaktorResikoController extends Controller
{
    public function index()
    {
        $faktorResiko = FaktorResiko::all();
        return response()->json($faktorResiko);
    }

    public function show($id)
    {
        $faktorResiko = FaktorResiko::find($id);

        if ($faktorResiko) {
            return response()->json($faktorResiko);
        } else {
            return response()->json(['message' => 'Faktor Resiko tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $faktorResiko = new FaktorResiko();
        $faktorResiko->fill($request->all());
        $faktorResiko->save();

        return response()->json(['message' => 'Faktor Resiko berhasil ditambahkan', 'data' => $faktorResiko]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $faktorResiko = FaktorResiko::find($id);

        if ($faktorResiko) {
            $faktorResiko->fill($request->all());
            $faktorResiko->save();

            return response()->json(['message' => 'Faktor Resiko berhasil diperbarui', 'data' => $faktorResiko]);
        } else {
            return response()->json(['message' => 'Faktor Resiko tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $faktorResiko = FaktorResiko::find($id);

        if ($faktorResiko) {
            $faktorResiko->delete();
            return response()->json(['message' => 'Faktor Resiko berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Faktor Resiko tidak ditemukan'], 404);
        }
    }
}
