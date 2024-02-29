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
        if (strpos('rlnmwy', $kataPerintah[0]) !== false) {
            return 'Me' . $kataPerintah;
        } elseif (strpos('bfv', $kataPerintah[0]) !== false) {
            return 'Mem' . $kataPerintah;
        } elseif (strpos('cdjz', $kataPerintah[0]) !== false) {
            return 'Men' . $kataPerintah;
        } elseif (strpos('aioueghq', $kataPerintah[0]) !== false) {
            return 'Meng' . $kataPerintah;
        } elseif (strpos('s', $kataPerintah[0]) !== false) {
            return 'Meny' . substr($kataPerintah, 1); // Menggunakan substr() untuk mengambil bagian dari kata setelah huruf 's'.
        } elseif (strpos('kt', $kataPerintah[0]) !== false) {
            return 'Me' . $kataPerintah;
        } else {
            return 'Me' . $kataPerintah;
        }
    }

    public function peluruhan($kataPerintah)
    {
        // Jika input berisi beberapa kata yang dipisahkan dengan koma, pecah menjadi array
        $kataPerintahArray = explode(', ', $kataPerintah);

        // Peluruhan setiap kata dalam array (konversi ke lowercase)
        $kataPerintahArray = array_map(function ($kata) {
            return strtolower($kata );
        }, $kataPerintahArray);

        // Peluruhan setiap kata dalam array
        $hasilPeluruhan = array_map([$this, 'peluruhanKata'], $kataPerintahArray);

        // Gabungkan semua hasil peluruhan menjadi satu string dipisahkan dengan koma
        return implode(', ', $hasilPeluruhan);
    }
}
