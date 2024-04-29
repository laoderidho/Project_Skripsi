<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Luaran;
// models admin log
use App\Models\AdminLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LuaranController extends Controller
{
    public function index()
    {
        $luaran = Luaran::all();
        return response()->json($luaran);
    }

    public function show($id)
    {
        $luaran = Luaran::find($id);

        if ($luaran) {
            return response()->json($luaran);
        } else {
            return response()->json(['message' => 'Luaran tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_luaran' => 'required|string|max:255|unique',
            'nama_luaran' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $luaran = new Luaran();
        $luaran->fill($request->all());

        // models admin log
        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Menambah luaran',
        ]);
        $luaran->save();

        return response()->json(['message' => 'Luaran berhasil ditambahkan', 'data' => $luaran]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_luaran' => 'required|string|max:255',
            'nama_luaran' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $luaran = Luaran::find($id);

        if ($luaran) {
            $luaran->fill($request->all());
            $luaran->save();

            // models admin log
            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Mengubah luaran',
            ]);

            return response()->json(['message' => 'Luaran berhasil diperbarui', 'data' => $luaran]);
        } else {
            return response()->json(['message' => 'Luaran tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $luaran = Luaran::find($id);

        if ($luaran) {
            $luaran->delete();

            // models admin log
            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Menghapus luaran',
            ]);
            return response()->json(['message' => 'Luaran berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Luaran tidak ditemukan'], 404);
        }
    }
}
