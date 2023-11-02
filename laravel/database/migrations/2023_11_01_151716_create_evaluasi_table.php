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
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_luaran');
            $table->unsignedBigInteger('id_penilaian_kriteria');
            $table->string('subjektif', 255);
            $table->string('objektif', 255);
            $table->string('pencapaian', 255);
            $table->string('perencanaan', 255);
            $table->string('catatan_evaluasi', 255);
            $table->timestamps();

            $table->foreign('id_luaran')->references('id')->on('luaran');
            $table->foreign('id_penilaian_kriteria')->references('id')->on('penilaian_kriteria');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};
