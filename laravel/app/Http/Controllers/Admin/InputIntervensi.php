<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\KategoriTindakan;
use App\Models\Admin\Intervensi;
use App\Models\Admin\TindakanIntervensi;

class InputIntervensiController extends Controller
{
    public function AddIntervensi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_intervensi' => 'required|string|max:255|unique:intervensi,nama_intervensi',
            'definisi_intervensi' => 'required|string|max:255|unique:intervensi,definisi_intervensi',
            'tindakan_observasi' => 'string|nullable',
            'tindakan_terapeutik' => 'string|nullable',
            'tindakan_edukasi' => 'string|nullable',
            'tindakan_kolaborasi' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Simpan intervensi ke dalam tabel intervensi
        $intervensi = new Intervensi();
        $intervensi->nama_intervensi = $request->input('nama_intervensi');
        $intervensi->kategori_tindakan_id = $request->input('kategori_tindakan_id');
        $intervensi->definisi_intervensi = $request->input('definisi_intervensi');
        $intervensi->save();

        // Simpan tindakan intervensi (Observasi)
        if ($request->has('tindakan_observasi')) {
            $this->saveTindakanIntervensi($request->input('tindakan_observasi'), 'Observasi', $intervensi->id);
        }

        // Simpan tindakan intervensi (Terapeutik)
        if ($request->has('tindakan_terapeutik')) {
            $this->saveTindakanIntervensi($request->input('tindakan_terapeutik'), 'Terapeutik', $intervensi->id);
        }

        // Simpan tindakan intervensi (Edukasi)
        if ($request->has('tindakan_edukasi')) {
            $this->saveTindakanIntervensi($request->input('tindakan_edukasi'), 'Edukasi', $intervensi->id);
        }

        // Simpan tindakan intervensi (Kolaborasi)
        if ($request->has('tindakan_kolaborasi')) {
            $this->saveTindakanIntervensi($request->input('tindakan_kolaborasi'), 'Kolaborasi', $intervensi->id);
        }

        return response()->json(['message' => 'Intervensi berhasil ditambahkan', 'data' => $intervensi]);
    }

    public function getIntervensi($id)
    {
        $intervensi = Intervensi::find($id);

        if (!$intervensi) {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }

        return response()->json(['data' => $intervensi]);
    }

    public function updateIntervensi(Request $request, $id)
    {
        $intervensi = Intervensi::find($id);

        if (!$intervensi) {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_intervensi' => 'required|string|max:255|unique:intervensi,nama_intervensi,' . $id,
            'definisi_intervensi' => 'required|string|max:255|unique:intervensi,definisi_intervensi,' . $id,
            'tindakan_observasi' => 'string|nullable',
            'tindakan_terapeutik' => 'string|nullable',
            'tindakan_edukasi' => 'string|nullable',
            'tindakan_kolaborasi' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update data intervensi
        $intervensi->nama_intervensi = $request->input('nama_intervensi');
        $intervensi->kategori_tindakan_id = $request->input('kategori_tindakan_id');
        $intervensi->definisi_intervensi = $request->input('definisi_intervensi');
        $intervensi->save();

        // Menghapus tindakan intervensi terkait yang sudah ada
        TindakanIntervensi::where('intervensi_id', $id)->delete();

        // Menyimpan tindakan intervensi baru sesuai kategori

        if ($request->has('tindakan_observasi')) {
            $this->saveTindakanIntervensi($request->input('tindakan_observasi'), 'Observasi', $intervensi->id);
        }

        if ($request->has('tindakan_terapeutik')) {
            $this->saveTindakanIntervensi($request->input('tindakan_terapeutik'), 'Terapeutik', $intervensi->id);
        }

        if ($request->has('tindakan_edukasi')) {
            $this->saveTindakanIntervensi($request->input('tindakan_edukasi'), 'Edukasi', $intervensi->id);
        }

        if ($request->has('tindakan_kolaborasi')) {
            $this->saveTindakanIntervensi($request->input('tindakan_kolaborasi'), 'Kolaborasi', $intervensi->id);
        }

        return response()->json(['message' => 'Intervensi berhasil diperbarui', 'data' => $intervensi]);
    }



    public function deleteIntervensi($id)
    {
        $intervensi = Intervensi::find($id);

        if (!$intervensi) {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }

        // Menghapus tindakan intervensi terkait yang sudah ada
        TindakanIntervensi::where('intervensi_id', $id)->delete();

        // Menghapus intervensi
        $intervensi->delete();

        return response()->json(['message' => 'Intervensi berhasil dihapus']);
    }


    private function saveTindakanIntervensi($namaTindakan, $kategori, $intervensiId)
    {
        $tindakan = new TindakanIntervensi();
        $tindakan->nama_tindakan_intervensi = $namaTindakan;
        $tindakan->kategori_tindakan = $kategori;
        $tindakan->id_kategori_tindakan = KategoriTindakan::where('nama_kategori_tindakan', $kategori)->first()->id;
        $tindakan->intervensi_id = $intervensiId;
        $tindakan->save();
    }


}
