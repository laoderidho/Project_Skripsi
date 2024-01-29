<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyebabTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['psikologis', 'situasional', 'fisiologis', 'umum'];

        foreach ($data as $d) {
            DB::table('jenis_penyebab')->insert([
                'nama_jenis_penyebab' => $d,
            ]);
        }
    }
}
