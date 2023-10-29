<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perawat\FormDiagnosa;
use Illuminate\Support\Facades\Validator;

class FormDiagnosaController extends Controller
{
    public function index()
    {
        $formDiagnosa = FormDiagnosa::all();
        return response()->json($formDiagnosa);
    }

    public function show($id)
    {
        $formDiagnosa = FormDiagnosa::find($id);

        if ($formDiagnosa) {
            return response()->json($formDiagnosa);
        } else {
            return response()->json(['message' => 'Form Diagnosa tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'id_gejala' => 'required|int',
            'id_detail_penyebab' => 'required|int',
            'id_faktor_resiko' => 'required|int',
            'catatan_diagnosa' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $formDiagnosa = new FormDiagnosa();
        $formDiagnosa->fill($request->all());
        $formDiagnosa->save();

        return response()->json(['message' => 'Form Diagnosa berhasil ditambahkan', 'data' => $formDiagnosa]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_diagnosa' => 'required|string|max:255',
            'id_gejala' => 'required|int',
            'id_detail_penyebab' => 'required|int',
            'id_faktor_resiko' => 'required|int',
            'catatan_diagnosa' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $formDiagnosa = FormDiagnosa::find($id);

        if ($formDiagnosa) {
            $formDiagnosa->fill($request->all());
            $formDiagnosa->save();

            return response()->json(['message' => 'Form Diagnosa berhasil diperbarui', 'data' => $formDiagnosa]);
        } else {
            return response()->json(['message' => 'Form Diagnosa tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        $formDiagnosa = FormDiagnosa::find($id);

        if ($formDiagnosa) {
            $formDiagnosa->delete();
            return response()->json(['message' => 'Form Diagnosa berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Form Diagnosa tidak ditemukan'], 404);
        }
    }
}
