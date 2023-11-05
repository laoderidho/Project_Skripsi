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
    public function tambahIntervensi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_intervensi' => 'required|string|max:255|unique:intervensi,nama_intervensi',
            'deskripsi' => 'required|string|max:255|unique:intervensi,deskripsi',
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
        $intervensi->deskripsi = $request->input('deskripsi');
        $intervensi->save();

        // Simpan tindakan intervensi
        $this->saveTindakanIntervensi($request, $intervensi->id);

        return response()->json(['message' => 'Intervensi berhasil ditambahkan', 'data' => $intervensi]);
    }

    public function readIntervensi($id)
    {
        $intervensi = Intervensi::with('kategoriTindakan', 'tindakanIntervensi')->find($id);

        if (!$intervensi) {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }

        return response()->json(['data' => $intervensi]);
    }

    public function editIntervensi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_intervensi' => 'required|string|max:255',
            'kategori_tindakan_id' => 'required|exists:kategori_tindakan,id',
            'tindakan_observasi' => 'string|nullable',
            'tindakan_terapeutik' => 'string|nullable',
            'tindakan_edukasi' => 'string|nullable',
            'tindakan_kolaborasi' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $intervensi = Intervensi::find($id);

        if (!$intervensi) {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }

        $intervensi->nama_intervensi = $request->input('nama_intervensi');
        $intervensi->kategori_tindakan_id = $request->input('kategori_tindakan_id');
        $intervensi->deskripsi = $request->input('deskripsi');
        $intervensi->save();

        // Update tindakan intervensi
        TindakanIntervensi::where('intervensi_id', $id)->delete();
        $this->saveTindakanIntervensi($request, $id);

        return response()->json(['message' => 'Intervensi berhasil diperbarui', 'data' => $intervensi]);
    }

    public function hapusIntervensi($id)
    {
        $intervensi = Intervensi::find($id);

        if (!$intervensi) {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }

        // Hapus tindakan intervensi yang terkait dengan intervensi
        TindakanIntervensi::where('intervensi_id', $id)->delete();

        // Hapus intervensi
        $intervensi->delete();

        return response()->json(['message' => 'Intervensi berhasil dihapus']);
    }

    private function saveTindakanIntervensi(Request $request, $intervensiId)
    {
        $kategoriTindakan = KategoriTindakan::where('nama_kategori_tindakan', 'Observasi')->first();
        if ($kategoriTindakan && $request->has('tindakan_observasi')) {
            $this->saveTindakan($request->input('tindakan_observasi'), $kategoriTindakan->id, $intervensiId);
        }

        $kategoriTindakan = KategoriTindakan::where('nama_kategori_tindakan', 'Terapeutik')->first();
        if ($kategoriTindakan && $request->has('tindakan_terapeutik')) {
            $this->saveTindakan($request->input('tindakan_terapeutik'), $kategoriTindakan->id, $intervensiId);
        }

        $kategoriTindakan = KategoriTindakan::where('nama_kategori_tindakan', 'Edukasi')->first();
        if ($kategoriTindakan && $request->has('tindakan_edukasi')) {
            $this->saveTindakan($request->input('tindakan_edukasi'), $kategoriTindakan->id, $intervensiId);
        }

        $kategoriTindakan = KategoriTindakan::where('nama_kategori_tindakan', 'Kolaborasi')->first();
        if ($kategoriTindakan && $request->has('tindakan_kolaborasi')) {
            $this->saveTindakan($request->input('tindakan_kolaborasi'), $kategoriTindakan->id, $intervensiId);
        }
    }

    private function saveTindakan($tindakan, $kategoriId, $intervensiId)
    {
        $tindakanArray = explode(PHP_EOL, $tindakan);
        foreach ($tindakanArray as $item) {
            $tindakanIntervensi = new TindakanIntervensi();
            $tindakanIntervensi->intervensi_id = $intervensiId;
            $tindakanIntervensi->kategori_tindakan_id = $kategoriId;
            $tindakanIntervensi->tindakan = trim($item);
            $tindakanIntervensi->save();
        }
    }
}
