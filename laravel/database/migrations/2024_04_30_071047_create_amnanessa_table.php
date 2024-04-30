<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('amnanessa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->constrained('pasien','id');
            $table->string('keluhan_utama', 1000)->nullable();
            $table->string('riwayat_penyakit', 1000)->nullable();
            $table->string('riwayat_alergi', 1000)->nullable();
            $table->string('risiko_jatuh', 1000)->nullable();
            $table->string('risiko_nyeri', 1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amnanessa');
    }
};
