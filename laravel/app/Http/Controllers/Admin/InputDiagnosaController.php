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
    public function AddDiagnosa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosa,kode_diagnosa',
            'nama_diagnosa' => 'required|string|max:255|unique:diagnosa,nama_diagnosa',
            'faktor_resiko' => 'string|nullable',
            'penyebab_psikologis' => 'string|nullable',
            'penyebab_situasional' => 'string|nullable',
            'penyebab_fisiologis' => 'string|nullable',
            'gejala_mayor_subjektif' => 'string|nullable',
            'gejala_mayor_objektif' => 'string|nullable',
            'gejala_minor_subjektif' => 'string|nullable',
            'gejala_minor_objektif' => 'string|nullable',

        ]);


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

    public function getDiagnosa($id)
    {
        $diagnosa = Diagnosa::where('id', $id)->first();

        if (!$diagnosa) {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }
        // Ubah data gejala menjadi array
        $gejalaMayorSubjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 1) // Sesuaikan dengan ID jenis gejala mayor
            ->where('id_kategori_gejala', 1) // Sesuaikan dengan ID kategori gejala subjektif
            ->pluck('nama_gejala')
            ->toArray();

        $gejalaMayorObjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 1) // Sesuaikan dengan ID jenis gejala mayor
            ->where('id_kategori_gejala', 2) // Sesuaikan dengan ID kategori gejala objektif
            ->pluck('nama_gejala')
            ->toArray();

        $gejalaMinorSubjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 2) // Sesuaikan dengan ID jenis gejala minor
            ->where('id_kategori_gejala', 1) // Sesuaikan dengan ID kategori gejala subjektif
            ->pluck('nama_gejala')
            ->toArray();

        $gejalaMinorObjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 2) // Sesuaikan dengan ID jenis gejala minor
            ->where('id_kategori_gejala', 2) // Sesuaikan dengan ID kategori gejala objektif
            ->pluck('nama_gejala')
            ->toArray();

        // Ubah data penyebab menjadi array
        $penyebabPsikologis = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 1) // Sesuaikan dengan ID jenis penyebab psikologis
            ->pluck('nama_penyebab')
            ->toArray();

        $penyebabSituasional = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 2) // Sesuaikan dengan ID jenis penyebab situasional
            ->pluck('nama_penyebab')
            ->toArray();

        $penyebabFisiologis = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 3) // Sesuaikan dengan ID jenis penyebab fisiologis
            ->pluck('nama_penyebab')
            ->toArray();
        $faktorResiko = FaktorResiko::where('id_diagnosa',$id)
            ->pluck('nama')
            ->toArray();
        // Mengganti nilai gejala dan penyebab dalam objek diagnosa
        $diagnosa->gejala_mayor_subjektif = $gejalaMayorSubjektif;
        $diagnosa->gejala_mayor_objektif = $gejalaMayorObjektif;
        $diagnosa->gejala_minor_subjektif = $gejalaMinorSubjektif;
        $diagnosa->gejala_minor_objektif = $gejalaMinorObjektif;
        $diagnosa->penyebab_psikologis = $penyebabPsikologis;
        $diagnosa->penyebab_situasional = $penyebabSituasional;
        $diagnosa->penyebab_fisiologis = $penyebabFisiologis;
        $diagnosa->faktor_resiko = $faktorResiko;
        return response()->json(['data' => $diagnosa]);
    }

    //Edit
    public function editDiagnosa(Request $request, $id)
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

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Temukan diagnosa berdasarkan ID
        $diagnosa = Diagnosa::find($id);

        if (!$diagnosa) {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }

        // Perbarui data diagnosa
        $diagnosa->kode_diagnosa = $request->input('kode_diagnosa');
        $diagnosa->nama_diagnosa = $request->input('nama_diagnosa');
        $diagnosa->save();

        // Faktor Resiko
        $this->updateFaktorResiko($request->input('faktor_resiko'), $diagnosa->id);

        // Gejala Mayor Subjektif
        $this->updateGejala($request->input('gejala_mayor_subjektif'), 'Mayor', 'Subjektif', $diagnosa->id);

        // Gejala Mayor Objektif
        $this->updateGejala($request->input('gejala_mayor_objektif'), 'Mayor', 'Objektif', $diagnosa->id);

        // Gejala Minor Subjektif
        $this->updateGejala($request->input('gejala_minor_subjektif'), 'Minor', 'Subjektif', $diagnosa->id);

        // Gejala Minor Objektif
        $this->updateGejala($request->input('gejala_minor_objektif'), 'Minor', 'Objektif', $diagnosa->id);

        // Penyebab Psikologis
        $this->updatePenyebab($request->input('penyebab_psikologis'), 'Psikologis', $diagnosa->id);

        // Penyebab Situasional
        $this->updatePenyebab($request->input('penyebab_situasional'), 'Situasional', $diagnosa->id);

        // Penyebab Fisiologis
        $this->updatePenyebab($request->input('penyebab_fisiologis'), 'Fisiologis', $diagnosa->id);

        return response()->json(['message' => 'Diagnosa berhasil diperbarui', 'data' => $diagnosa]);
    }

    public function hapusDiagnosa($id)
    {
        // Temukan diagnosa berdasarkan ID
        $diagnosa = Diagnosa::find($id);

        if (!$diagnosa) {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }

        // Hapus faktor resiko yang terkait dengan diagnosa
        FaktorResiko::where('id_diagnosa', $id)->delete();

        // Hapus gejala yang terkait dengan diagnosa
        Gejala::where('id_diagnosa', $id)->delete();

        // Hapus penyebab yang terkait dengan diagnosa
        DetailPenyebab::where('id_diagnosa', $id)->delete();

        // Hapus diagnosa
        $diagnosa->delete();

        return response()->json(['message' => 'Diagnosa berhasil dihapus']);
    }

    private function updateFaktorResiko($faktorResiko, $diagnosaId)
    {
        // Hapus faktor resiko yang terkait dengan diagnosa
        FaktorResiko::where('id_diagnosa', $diagnosaId)->delete();

        // Simpan faktor resiko baru
        $faktorResikoArray = explode(PHP_EOL, $faktorResiko);
        foreach ($faktorResikoArray as $item) {
            $faktorResikoModel = new FaktorResiko();
            $faktorResikoModel->id_diagnosa = $diagnosaId;
            $faktorResikoModel->nama = $item;
            $faktorResikoModel->save();
        }
    }

    private function updateGejala($gejalaInput, $jenisGejala, $kategoriGejala, $diagnosaId)
    {
        // Hapus gejala yang terkait dengan diagnosa
        Gejala::where('id_diagnosa', $diagnosaId)
            ->where('id_jenis_gejala', JenisGejala::where('nama_jenis_gejala', $jenisGejala)->first()->id)
            ->where('id_kategori_gejala', KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->first()->id)
            ->delete();

        // Simpan gejala baru
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

    private function updatePenyebab($penyebabInput, $jenisPenyebab, $diagnosaId)
    {
        // Hapus penyebab yang terkait dengan diagnosa
        DetailPenyebab::where('id_diagnosa', $diagnosaId)
            ->where('id_jenis_penyebab', JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first()->id)
            ->delete();

        // Simpan penyebab baru
        $penyebabArray = explode(PHP_EOL, $penyebabInput);
        foreach ($penyebabArray as $item) {
            $penyebabModel = new DetailPenyebab();
            $penyebabModel->id_diagnosa = $diagnosaId;
            $penyebabModel->id_jenis_penyebab = JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first()->id;
            $penyebabModel->nama_penyebab = $item;
            $penyebabModel->save();
        }
    }

    private function saveFaktorResiko($faktorResiko, $diagnosaId)
    {
        $faktorResikoArray = explode(PHP_EOL, $faktorResiko);
        foreach ($faktorResikoArray as $item) {
            // Menghindari menyimpan baris kosong
            $trimmedItem = trim($item);

            $faktorResikoModel = new FaktorResiko();
            $faktorResikoModel->id_diagnosa = $diagnosaId;
            $faktorResikoModel->nama = $trimmedItem;
            $faktorResikoModel->save();
        }
    }



        private function saveGejala($gejalaInput, $jenisGejala, $kategoriGejala, $diagnosaId)
        {
            try {
                $jenisGejalaModel = JenisGejala::where('nama_jenis_gejala', $jenisGejala)->first();
                $kategoriGejalaModel = KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->first();

                if ($jenisGejalaModel && $kategoriGejalaModel) {
                    $jenisGejalaId = $jenisGejalaModel->id;
                    $kategoriGejalaId = $kategoriGejalaModel->id; // Menggunakan properti id dari objek KategoriGejala
                } else {
                    // Handle ketika jenis gejala atau kategori gejala tidak ditemukan
                    return response()->json(['error' => 'Jenis gejala / Kategori tidak ditemukan'], 404);
                }

                $gejalaArray = explode(PHP_EOL, $gejalaInput);
                foreach ($gejalaArray as $item) {
                    $trimmedItem = trim($item);
                    if (!empty($trimmedItem)) {
                        $gejalaModel = new Gejala();
                        $gejalaModel->id_diagnosa = $diagnosaId;
                        $gejalaModel->id_jenis_gejala = $jenisGejalaId;
                        $gejalaModel->id_kategori_gejala = $kategoriGejalaId;
                        $gejalaModel->nama_gejala = $trimmedItem;
                        $gejalaModel->save();
                    }
                }

            } catch (Exception $e) {
                return response()->json(['error' => 'Null'], 404);
            }
        }


            private function savePenyebab($penyebabInput, $jenisPenyebab, $diagnosaId)
            {
                $jenisPenyebabModel = JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first();
                if ($jenisPenyebabModel) {
                    $jenisPenyebabModel = $jenisPenyebabModel->id;
                    // Lanjutkan dengan penggunaan $jenisGejalaId
                } else {
                    // Handle ketika jenis gejala tidak ditemukan
                    return response()->json(['error' => 'Jenis Penyebab tidak ditemukan'], 404);
                }


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
