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
            $table->unsignedBigInteger('id_diagnosa');
            $table->unsignedBigInteger('id_pemeriksaan');
            $table->string('nama_diagnosa', 255)->nullable();
            $table->string('gejala', 5000)->nullable();
            $table->string('penyebab', 5000)->nullable();
            $table->string('faktor_risiko', 5000)->nullable();
            $table->string('catatan_diagnosa', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_diagnosa')->references('id')->on('diagnosa');
            $table->foreign('id_pemeriksaan')->references('id')->on('pemeriksaan');
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
