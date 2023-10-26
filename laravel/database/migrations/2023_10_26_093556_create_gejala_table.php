<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gejala', function (Blueprint $table) {
            $table->id('id_gejala');
            $table->unsignedBigInteger('id_diagnosa');
            $table->unsignedBigInteger('id_kategori_gejala');
            $table->unsignedBigInteger('id_jenis_gejala');
            $table->string('nama_gejala');
            $table->timestamps();
        });

        // Schema::table('gejala', function (Blueprint $table) {
        //     $table->foreign('id_diagnosa')->references('id_diagnosa')->on('diagnosa');
        //     $table->foreign('id_kategori_gejala')->references('id_kategori_gejala')->on('kategori_gejala');
        //     $table->foreign('id_jenis_gejala')->references('id_jenis_gejala')->on('jenis_gejala');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('gejala');
    }
};
