<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_penyebab', function (Blueprint $table) {
            $table->id('id_detail_penyebab');
            $table->unsignedBigInteger('id_diagnosa');
            $table->unsignedBigInteger('id_jenis_penyebab');
            $table->integer('nama_penyebab');
            $table->timestamps();
        });

        Schema::table('detail_penyebab', function (Blueprint $table) {
            $table->foreign('id_diagnosa')->references('id_diagnosa')->on('diagnosa');
            $table->foreign('id_jenis_penyebab')->references('id_jenis_penyebab')->on('jenis_penyebab');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penyebab');
    }
};
