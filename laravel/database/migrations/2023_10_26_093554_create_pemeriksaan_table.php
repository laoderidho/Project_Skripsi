<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id('id_pemeriksaan');
            $table->unsignedBigInteger('id_perawat');
            $table->unsignedBigInteger('id_form_diagnosa');
            $table->unsignedBigInteger('id_tindakan_intervensi');
            $table->unsignedBigInteger('id_implementasi');
            $table->unsignedBigInteger('id_evaluasi');
            $table->time('jam_pemberian_diagnosa');
            $table->time('jam_pemberian_gejala');
            $table->time('jam_pemberian_implementasi');
            $table->time('jam_penilaian_evaluasi');
            $table->timestamps();
        });

        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->foreign('id_perawat')->references('id_perawat')->on('perawat');
            $table->foreign('id_form_diagnosa')->references('id_form_diagnosa')->on('form_diagnosa');
            $table->foreign('id_tindakan_intervensi')->references('id_tindakan_intervensi')->on('tindakan_intervensi');
            $table->foreign('id_implementasi')->references('id_implementasi')->on('implementasi');
            $table->foreign('id_evaluasi')->references('id_evaluasi')->on('evaluasi');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
