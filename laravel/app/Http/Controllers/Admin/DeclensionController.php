<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Implementasi;
use App\Models\Intervensi;

class DeclensionController extends Controller
{
    private function peluruhanKata($kataPerintah)
    {
        // Daftar bunyi nasal
        $bunyiNasal = ['m', 'n', 'ng', 'ny'];

        // Ambil huruf pertama dan terakhir dari kata perintah
        $hurufPertama = substr($kataPerintah, 0, 1);
        $akhirKataPerintah = substr($kataPerintah, -1);

        // Tentukan awalan berdasarkan aturan yang telah diberikan
        if (in_array($hurufPertama, $bunyiNasal)) {
            if ($akhirKataPerintah == 'i') {
                return 'meng' . $kataPerintah;
            } else {
                return 'me' . $kataPerintah;
            }
        } else {
            if (in_array($akhirKataPerintah, ['i', 'kan'])) {
                return 'men' . $kataPerintah;
            } elseif (in_array($hurufPertama, ['b', 'f', 'v', 'p'])) {
                return 'mem' . $kataPerintah;
            } elseif (in_array($hurufPertama, ['c', 'd', 'j', 'z', 't'])) {
                return 'men' . $kataPerintah;
            } elseif (in_array($hurufPertama, ['g', 'h', 'q', 'k'])) {
                return 'meng' . $kataPerintah;
            } elseif ($hurufPertama == 'r') {
                return 'me' . $kataPerintah;
            } elseif ($hurufPertama == 'l') {
                return 'me' . $kataPerintah;
            } elseif ($hurufPertama == 's') {
                return 'meny' . substr($kataPerintah, 1);
            } else {
                return 'me' . $kataPerintah;
            }
        }

        // Jika tidak ada aturan yang cocok, kembalikan kata perintah saja
        return $kataPerintah;
    }

    public function peluruhan($kataPerintah)
    {
        // Jika input berisi beberapa kata yang dipisahkan dengan koma, pecah menjadi array
        $kataPerintahArray = explode(', ', $kataPerintah);

        // Peluruhan setiap kata dalam array (konversi ke lowercase)
        $kataPerintahArray = array_map(function ($kata) {
            return strtolower($kata);
        }, $kataPerintahArray);

        // Peluruhan setiap kata dalam array
        $hasilPeluruhan = array_map([$this, 'peluruhanKata'], $kataPerintahArray);

        // Gabungkan semua hasil peluruhan menjadi satu string dipisahkan dengan koma
        return implode(', ', $hasilPeluruhan);
    }
}
