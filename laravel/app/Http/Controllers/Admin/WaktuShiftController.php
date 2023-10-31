<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaktuShift;
use Illuminate\Support\Facades\Validator;

class WaktuShiftController extends Controller
{
    public function index()
    {
        $waktuShift = WaktuShift::all();
        return response()->json($waktuShift);
    }

    public function show($id)
    {
        $waktuShift = WaktuShift::find($id);

        if ($waktuShift) {
            return response()->json($waktuShift);
        } else {
            return response()->json(['message' => 'Waktu Shift tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|date',
            'tanggal' => 'required|date',
            'shift' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $waktuShift = new WaktuShift();
        $waktuShift->fill($request->all());
        $waktuShift->save();

        return response()->json(['message' => 'Waktu Shift berhasil ditambahkan', 'data' => $waktuShift]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hari' => 'required|date',
            'tanggal' => 'required|date',
            'shift' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $waktuShift = WaktuShift::find($id);

        if ($waktuShift) {
            $waktuShift->fill($request->all());
            $waktuShift->save();

            return response()->json(['message' => 'Waktu Shift berhasil diperbarui', 'data' => $waktuShift]);
        } else {
            return response()->json(['message' => 'Waktu Shift tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $waktuShift = WaktuShift::find($id);

        if ($waktuShift) {
            $waktuShift->delete();
            return response()->json(['message' => 'Waktu Shift berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Waktu Shift tidak ditemukan'], 404);
        }
    }
}
