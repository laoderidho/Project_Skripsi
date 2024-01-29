<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\WaktuShift;
use Illuminate\Support\Facades\DB;

class WaktuShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $shifts = [1, 2, 3];

        // Memasukkan data ke dalam tabel shifts
        foreach ($shifts as $shift) {
            DB::table('waktu_shift')->insert([
                'shift' => $shift,
            ]);
        }
    }
}
