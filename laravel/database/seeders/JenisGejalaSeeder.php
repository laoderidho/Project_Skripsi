<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisGejalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['Mayor', 'Minor'];

        foreach ($data as $d) {
            DB::table('jenis_gejala')->insert([
                'nama_jenis_gejala' => $d,
            ]);
        }
    }
}
