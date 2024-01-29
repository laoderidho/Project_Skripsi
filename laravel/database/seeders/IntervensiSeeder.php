<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\KategoriTindakan;
use Illuminate\Support\Facades\DB;

class IntervensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = ['Observasi', 'Terapeutik', 'Edukasi', 'Kolaborasi'];

        foreach ($data as $d) {
           DB::table('kategori_tindakan')->insert([
                'nama_kategori_tindakan' => $d,
            ]);
        }
    }
}
