<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'nama_lengkap' => 'admin',
            'tanggal_lahir' => '2002-08-26',
            'jenis_kelamin' => 0,
            'alamat' => 'Jl. Raya Cikarang - Cibarusah, Cikarang Selatan, Bekasi',
            'no_telepon' => '081234567890',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@123'),
            'no_karyawan' => 'ADM001',
            'role' => 'admin',
        ]);
    }
}
