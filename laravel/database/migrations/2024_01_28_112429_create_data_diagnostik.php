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
        Schema::create('data_diagnostik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasien')->constrained('pasien','id');
            $table->foreignId('id_perawat')->constrained('perawat','id');
            $table->string('keluhan_utama', 255);
            $table->string('riwayat_penyakit', 255);
            $table->string('riwayat_alergi', 255);
            $table->string('risiko_jatuh', 255);
            $table->string('risiko_nyeri', 255);
            $table->string('suhu', 3);
            $table->string('tekanan_darah', 255);
            $table->integer('sistolik');
            $table->integer('diastolik');
            $table->string('nadi', 255);
            $table->string('laju_respirasi', 255);
            $table->string('kesadaran', 255);
            $table->string('eye', 255);
            $table->string('motor', 255);
            $table->string('verbal', 255);
            $table->string('pemeriksaan_fisik', 255);
            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_diagnostik');
    }
};
