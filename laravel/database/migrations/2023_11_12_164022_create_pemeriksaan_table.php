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
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perawatan');
            $table->unsignedBigInteger('id_form_diagnosa');
            $table->unsignedBigInteger('id_tindakan_intervensi');
            $table->unsignedBigInteger('id_implementasi');
            $table->unsignedBigInteger('id_evaluasi');
            $table->time('jam_pemberian_diagnosa');
            $table->time('jam_pemberian_gejala');
            $table->time('jam_pemberian_implementasi');
            $table->time('jam_penilaian_evaluasi');
            $table->timestamps();

            $table->foreign('id_perawatan')->references('id')->on('perawat');
            $table->foreign('id_form_diagnosa')->references('id')->on('form_diagnosa');
            $table->foreign('id_tindakan_intervensi')->references('id')->on('tindakan_intervensi');
            $table->foreign('id_implementasi')->references('id')->on('implementasi');
            $table->foreign('id_evaluasi')->references('id')->on('evaluasi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
