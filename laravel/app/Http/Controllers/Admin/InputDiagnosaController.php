<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Diagnosa;
use App\Models\FaktorResiko;
use App\Models\Gejala;
use App\Models\JenisGejala;
use App\Models\KategoriGejala;
use App\Models\JenisPenyebab;
use App\Models\DetailPenyebab;

class InputDiagnosaController extends Controller
{
    public function tambahDiagnosa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255',
            'nama_diagnosa' => 'required|string|max:255',
            'id_faktor_resiko' => 'required|exists:faktor_resiko,id_faktor_resiko',
            'id_gejala' => 'required|exists:gejala,id_gejala',
            'id_kategori_gejala' => 'required|exists:kategori_gejala,id_kategori_gejala',
            'id_jenis_gejala' => 'required|exists:jenis_gejala,id_jenis_gejala',
            'id_jenis_penyebab' => 'required|exists:jenis_penyebab,id_jenis_penyebab',
            'gejala_nama' => 'required|string|max:255',
            'penyebab_psikologis' => 'required|string|max:255',
            'penyebab_situasional' => 'required|string|max:255',
            'penyebab_fisiologis' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $diagnosa = new Diagnosa();
        $diagnosa->kode_diagnosa = $request->input('kode_diagnosa');
        $diagnosa->nama_diagnosa = $request->input('nama_diagnosa');
        $diagnosa->save();

        $gejala = new Gejala();
        $gejala->id_diagnosa = $diagnosa->id;
        $gejala->id_jenis_gejala = $request->input('id_jenis_gejala');
        $gejala->id_kategori_gejala = $request->input('id_kategori_gejala');
        $gejala->nama_gejala = $request->input('gejala_nama');
        $gejala->save();

        $jenisPenyebab = new JenisPenyebab();
        $jenisPenyebab->id_diagnosa = $diagnosa->id;
        $jenisPenyebab->id_jenis_penyebab = $request->input('id_jenis_penyebab');
        $jenisPenyebab->save();

        $detailPenyebab = new DetailPenyebab();
        $detailPenyebab->id_diagnosa = $diagnosa->id;
        $detailPenyebab->penyebab_psikologis = $request->input('penyebab_psikologis');
        $detailPenyebab->penyebab_situasional = $request->input('penyebab_situasional');
        $detailPenyebab->penyebab_fisiologis = $request->input('penyebab_fisiologis');
        $detailPenyebab->save();

        return response()->json(['message' => 'Diagnosa berhasil ditambahkan', 'data' => $diagnosa]);
    }
}
