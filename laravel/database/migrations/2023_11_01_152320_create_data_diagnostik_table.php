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
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_perawat');
            $table->string('keluhan_utama', 255);
            $table->string('riwayat_penyakit', 255);
            $table->string('riwayat_alergi', 255);
            $table->string('risiko_jatuh', 255);
            $table->string('risiko_nyeri', 255);
            $table->string('suhu', 3);
            $table->string('tekanan_darah', 255);
            $table->string('nadi', 255);
            $table->string('laju_respirasi', 255);
            $table->string('kesadaran', 255);
            $table->string('eye', 255);
            $table->string('motor', 255);
            $table->string('visual', 255);
            $table->string('pemeriksaan_fisik', 255);
            $table->timestamps();

            $table->foreign('id_pasien')->references('id')->on('pasien');
            $table->foreign('id_perawat')->references('id')->on('perawat');


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
