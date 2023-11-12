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
        Schema::create('gejala', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_diagnosa')->unsigned();
            $table->unsignedBigInteger('id_kategori_gejala');
            $table->unsignedBigInteger('id_jenis_gejala');
            $table->string('nama_gejala', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_diagnosa')->references('id')->on('diagnosa');
            $table->foreign('id_kategori_gejala')->references('id')->on('kategori_gejala');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gejala');
    }
};
