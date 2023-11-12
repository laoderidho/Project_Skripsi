<?php

namespace App\Http\Controllers\Admin\Diagnosa;

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
use Illuminate\Support\Facades\DB;

class InputDiagnosaController extends Controller
{
    public function getDiagnosa()
    {
        $diagnosa = Diagnosa::all();

        return response()->json([
            'message' => 'Sukses',
            'data' => $diagnosa,
        ]);
    }

    public function AddDiagnosa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosa,kode_diagnosa',
            'nama_diagnosa' => 'required|string|max:255|unique:diagnosa,nama_diagnosa',
            'faktor_risiko' => 'string|nullable',
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
        DB::beginTransaction();
       try{
            $diagnosa = new Diagnosa();
            $diagnosa->kode_diagnosa = $request->input('kode_diagnosa');
            $diagnosa->nama_diagnosa = $request->input('nama_diagnosa');
            $diagnosa->save();
            $diagnosaId = $diagnosa->id; // Ambil ID diagnosa yang baru dibuat

            // Faktor Resiko
            $this->saveFaktorResiko($request->input('faktor_risiko'), $diagnosaId);


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

            DB::commit();

       }
       catch(\Exception $e){
            dd($e);
            DB::rollback();
       }
       return response()->json(['message' => 'Diagnosa berhasil ditambahkan', 'data' => $diagnosa]);
    }

    public function detailDiagnosa($id)
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
        $diagnosa->faktor_risiko = $faktorResiko;
        return response()->json(['data' => $diagnosa]);
    }

    //Edit
    public function updateDiagnosa(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosa,kode_diagnosa',
            'nama_diagnosa' => 'required|string|max:255|unique:diagnosa,nama_diagnosa',
            'faktor_risiko' => 'string|nullable',
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

        DB::beginTransaction();

        try{
            $diagnosa = Diagnosa::find($id);

            if (!$diagnosa) {
                return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
            }

            // Perbarui data diagnosa
            $diagnosa->kode_diagnosa = $request->input('kode_diagnosa');
            $diagnosa->nama_diagnosa = $request->input('nama_diagnosa');
            $diagnosa->save();

            // Faktor Resiko
            $this->updateFaktorResiko($request->input('faktor_risiko'), $diagnosa->id);

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

            DB::commit();
        }
        catch(\Exception $e){

        }
        // Temukan diagnosa berdasarkan ID

        return response()->json(['message' => 'Diagnosa berhasil diperbarui', 'data' => $diagnosa]);
    }

    public function hapusDiagnosa($id)
    {
        // Temukan diagnosa berdasarkan ID
        $diagnosa = Diagnosa::find($id);

        DB::beginTransaction();
        try{
            if (!$diagnosa) {
                return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
            }

            // Hapus faktor risiko yang terkait dengan diagnosa
            FaktorResiko::where('id_diagnosa', $id)->delete();

            // Hapus gejala yang terkait dengan diagnosa
            Gejala::where('id_diagnosa', $id)->delete();

            // Hapus penyebab yang terkait dengan diagnosa
            DetailPenyebab::where('id_diagnosa', $id)->delete();

            // Hapus diagnosa
            $diagnosa->delete();
            DB::commit();
        }
        catch(\Exception $e){
            dd($e);
            DB::rollback();
        }

        return response()->json(['message' => 'Diagnosa berhasil dihapus']);
    }

    private function updateFaktorResiko($faktorResiko, $diagnosaId)
    {
        // Hapus faktor risiko yang terkait dengan diagnosa
        FaktorResiko::where('id_diagnosa', $diagnosaId)->delete();

        // Simpan faktor risiko baru
        $faktorResikoArray = implode(PHP_EOL, $faktorResiko);
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
        $gejalaArray = implode(PHP_EOL, $gejalaInput);
        foreach ($gejalaArray as $item) {
            $gejalaModel = new Gejala();
            $gejalaModel->id_diagnosa = $diagnosaId;
            $gejalaModel->id_jenis_gejala = JenisGejala::where('nama_jenis_gejala', $jenisGejala)->first()->id;
            $gejalaModel->id_kategori_gejala = KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->first()->id;
            $gejalaModel->nama_gejala = trim($item); // Menggunakan trim
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
        $penyebabArray = implode(PHP_EOL, $penyebabInput);
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
        $request = request();

        $request->validate([
            'faktorResiko' => 'required|array',
            'faktorResiko.*' => 'string|max:255',
        ]);

        // Buat faktor risiko baru
        foreach ($request->input('faktorResiko') as $faktorResikoItem) {
            $faktorResikoModel = new FaktorResiko();
            $faktorResikoModel->id_diagnosa = $diagnosaId;
            $faktorResikoModel->nama = $faktorResikoItem;
            $faktorResikoModel->save();
        }
    }


    private function saveGejala($gejalaInput, $jenisGejala, $kategoriGejala, $diagnosaId)
    {
        $request = request();

        $request->validate([
            'gejala' => 'required|array',
            'gejala.*' => 'string|max:255',
        ]);

        // Cari jenis gejala dan kategori gejala
        $jenisGejalaModel = JenisGejala::where('nama_jenis_gejala', $jenisGejala)->first();
        $kategoriGejalaModel = KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->first();

        // Jika jenis gejala atau kategori gejala tidak ditemukan
        if (!$jenisGejalaModel || !$kategoriGejalaModel) {
            return response()->json(['error' => 'Jenis gejala / Kategori tidak ditemukan'], 404);
        }

        // Buat gejala baru
        foreach ($request->input('gejala') as $gejalaItem) {
            $gejalaModel = new Gejala();
            $gejalaModel->id_diagnosa = $diagnosaId;
            $gejalaModel->id_jenis_gejala = $jenisGejalaModel->id;
            $gejalaModel->id_kategori_gejala = $kategoriGejalaModel->id;
            $gejalaModel->nama_gejala = $gejalaItem;
            $gejalaModel->save();
        }
    }
    private function savePenyebab($penyebabInput, $jenisPenyebab, $diagnosaId)
    {
        $request = request();

        $request->validate([
            'penyebab' => 'required|array',
            'penyebab.*' => 'string|max:255',
        ]);

        // Cari jenis penyebab
        $jenisPenyebabModel = JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first();

        // Jika jenis penyebab tidak ditemukan
        if (!$jenisPenyebabModel) {
            return response()->json(['error' => 'Jenis Penyebab tidak ditemukan'], 404);
        }

        // Buat penyebab baru
        foreach ($request->input('penyebab') as $penyebabItem) {
            $penyebabModel = new DetailPenyebab();
            $penyebabModel->id_diagnosa = $diagnosaId;
            $penyebabModel->id_jenis_penyebab = $jenisPenyebabModel->id;
            $penyebabModel->nama_penyebab = $penyebabItem;
            $penyebabModel->save();
        }
    }


}
