<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_diagnostik', function (Blueprint $table) {
            $table->id('id_data_diagnostik');
            $table->unsignedBigInteger('id_pasien');
            $table->string('keluhan_utama', 255);
            $table->string('riwayat_penyakit', 255);
            $table->string('riwayat_alergi', 255);
            $table->string('resiko_jatuh', 255);
            $table->string('resiko_nyeri', 255);
            $table->string('suhu', 3);
            $table->string('tekanan_darah', 255);
            $table->string('nadi', 255);
            $table->string('laju_respirasi', 255);
            $table->string('kesadaran', 255);
            $table->string('gcs_eyes', 255);
            $table->string('gcs_motoric', 255);
            $table->string('gcs_visual', 255);
            $table->string('pemeriksaan_fisik', 255);
            $table->timestamps();
        });

        Schema::table('data_diagnostik', function (Blueprint $table) {
            $table->foreign('id_pasien')->references('id_pasien')->on('pasien');
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_diagnostik');
    }
};
