<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriGejalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['Subjektif', 'Objektif'];

        foreach ($data as $d) {
            DB::table('kategori_gejala')->insert([
                'nama_kategori_gejala' => $d,
            ]);
        }
    }
}
