<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Diagnosa;
use App\Models\Admin\FaktorResiko;
use App\Models\Admin\DetailPenyebab;
use App\Models\Admin\JenisPenyebab;
use App\Models\Admin\JenisGejala;
use App\Models\Admin\KategoriGejala;
use App\Models\Admin\Gejala;


class InputDiagnosaController extends Controller
{
    public function tambahDiagnosa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosa,kode_diagnosa',
            'nama_diagnosa' => 'required|string|max:255|unique:diagnosa,nama_diagnosa',
            'faktor_resiko' => 'string|nullable',
            'gejala_mayor_subjektif' => 'string|nullable',
            'gejala_mayor_objektif' => 'string|nullable',
            'gejala_minor_subjektif' => 'string|nullable',
            'gejala_minor_objektif' => 'string|nullable',
            'penyebab_psikologis' => 'string|nullable',
            'penyebab_situasional' => 'string|nullable',
            'penyebab_fisiologis' => 'string|nullable',
        ]);

//  Debugging
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Simpan diagnosa ke dalam tabel diagnosa
        $diagnosa = new Diagnosa();
        $diagnosa->kode_diagnosa = $request->input('kode_diagnosa');
        $diagnosa->nama_diagnosa = $request->input('nama_diagnosa');
        $diagnosa->save();
        $diagnosaId = $diagnosa->id; // Ambil ID diagnosa yang baru dibuat

        // Faktor Resiko
        $this->saveFaktorResiko($request->input('faktor_resiko'), $diagnosaId);

//private function saveGejala($gejalaInput, $jenisGejala, $kategoriGejala, $diagnosaId)

        // Gejala Mayor Subjektif
        $this->saveGejala($request->input('gejala_mayor_subjektif'), 'Mayor', 'Subjektif', $diagnosaId);

        // Gejala Mayor Objektif
        $this->saveGejala($request->input('gejala_mayor_objektif'), 'Mayor', 'Objektif', $diagnosaId);

        // Gejala Minor Subjektif
        $this->saveGejala($request->input('gejala_minor_subjektif'), 'Minor', 'Subjektif', $diagnosaId);

        // Gejala Minor Objektif
        $this->saveGejala($request->input('gejala_minor_objektif'), 'Minor', 'Objektif', $diagnosaId);

        // Penyebab Psikologis
        $this->savePenyebab($request->input('penyebab_psikologis'), 'Psikologis', $diagnosaId);

        // Penyebab Situasional
        $this->savePenyebab($request->input('penyebab_situasional'), 'Situasional', $diagnosaId);

        // Penyebab Fisiologis
        $this->savePenyebab($request->input('penyebab_fisiologis'), 'Fisiologis', $diagnosaId);

        return response()->json(['message' => 'Diagnosa berhasil ditambahkan', 'data' => $diagnosa]);
    }

    private function saveFaktorResiko($faktorResiko, $diagnosaId)
    {
        $faktorResikoArray = explode(PHP_EOL, $faktorResiko);
        foreach ($faktorResikoArray as $item) {
            $faktorResikoModel = new FaktorResiko();
            $faktorResikoModel->id_diagnosa = $diagnosaId;
            $faktorResikoModel->nama = $item;
            $faktorResikoModel->save();
        }
    }

    private function saveGejala($gejalaInput, $jenisGejala, $kategoriGejala, $diagnosaId)
    {
        $gejalaArray = explode(PHP_EOL, $gejalaInput);
        foreach ($gejalaArray as $item) {
            $gejalaModel = new Gejala();
            $gejalaModel->id_diagnosa = $diagnosaId;
            $gejalaModel->id_jenis_gejala = JenisGejala::where('nama_jenis_gejala', $jenisGejala)->first()->id;
            $gejalaModel->id_kategori_gejala = KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->first()->id;
            $gejalaModel->nama_gejala = $item;
            $gejalaModel->save();
        }
    }

    private function savePenyebab($penyebabInput, $jenisPenyebab, $diagnosaId)
    {
        $penyebabArray = explode(PHP_EOL, $penyebabInput);
        $jenisPenyebabId = JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first()->id;

        foreach ($penyebabArray as $item) {
            $penyebabModel = new DetailPenyebab();
            $penyebabModel->id_diagnosa = $diagnosaId;
            $penyebabModel->id_jenis_penyebab = $jenisPenyebabId;
            $penyebabModel->nama_penyebab = $item;
            $penyebabModel->save();
        }
    }
}
