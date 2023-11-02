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
            $table->unsignedBigInteger('id_gejala');
            $table->unsignedBigInteger('id_detail_penyebab');
            $table->unsignedBigInteger('id_faktor_resiko');
            $table->string('catatan_diagnosa', 255);
            $table->timestamps();

            $table->foreign('id_diagnosa')->references('id')->on('diagnosa');
            $table->foreign('id_gejala')->references('id')->on('gejala');
            $table->foreign('id_detail_penyebab')->references('id')->on('detail_penyebab');
            $table->foreign('id_faktor_resiko')->references('id')->on('faktor_resiko');

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
