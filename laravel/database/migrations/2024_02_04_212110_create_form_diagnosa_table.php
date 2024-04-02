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
        Schema::create('form_diagnosa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->unsignedBigInteger('nama_diagnosa');
            $table->text('gejala_tanda_mayor_objektif')->nullable();
            $table->text('gejala_tanda_mayor_subjektif')->nullable();
            $table->text('gejala_tanda_minor_objektif')->nullable();
            $table->text('gejala_tanda_minor_subjektif')->nullable();
            $table->text('penyebab_psikologis')->nullable();
            $table->text('penyebab_situasional')->nullable();
            $table->text('penyebab_fisiologis')->nullable();
            $table->text('penyebab_umum')->nullable();
            $table->text('faktor_risiko')->nullable();
            $table->string('catatan_diagnosa', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan');
            $table->foreign('nama_diagnosa')->references('id')->on('diagnosa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_diagnosa');
    }
};
