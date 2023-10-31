<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntervensiSeeder extends Seeder
{
    public function run()
    {
        $intervensis = [
            ['id_intervensi' => 'I.08233', 'nama_intervensi' => 'Aromaterapi'],
            ['id_intervensi' => 'I.02028', 'nama_intervensi' => 'Balut Tekan'],
            ['id_intervensi' => 'I.09254', 'nama_intervensi' => 'Biblioterapi'],
            ['id_intervensi' => 'I.12359', 'nama_intervensi' => 'Bimbingan Antisipatif'],
            ['id_intervensi' => 'I.12360', 'nama_intervensi' => 'Bimbingan Sistem Kesehatan'],
            ['id_intervensi' => 'I.02029', 'nama_intervensi' => 'Code Management'],
            ['id_intervensi' => 'I.13476', 'nama_intervensi' => 'Delegasi'],
            ['id_intervensi' => 'I.09255', 'nama_intervensi' => 'Diskusi Kelompok Terarah'],
            ['id_intervensi' => 'I.06171', 'nama_intervensi' => 'Dukungan Ambulasi'],
            ['id_intervensi' => 'I.09256', 'nama_intervensi' => 'Dukungan Emosional'],
            ['id_intervensi' => 'I.09257', 'nama_intervensi' => 'Dukungan Hipnosis Diri'],
            ['id_intervensi' => 'I.09258', 'nama_intervensi' => 'Dukungan Kelompok'],
            ['id_intervensi' => 'I.13477', 'nama_intervensi' => 'Dukungan Keluarga Merencanakan Perawatan'],
            ['id_intervensi' => 'I.12361', 'nama_intervensi' => 'Dukungan Kepatuhan Program Pengobatan'],
            ['id_intervensi' => 'I.09259', 'nama_intervensi' => 'Dukungan Keyakinan'],
        ];

        // Insert data ke dalam tabel 'intervensis'
        DB::table('intervensi')->insert($intervensis);
    }
}
