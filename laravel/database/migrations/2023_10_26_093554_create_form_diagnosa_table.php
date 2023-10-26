<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('form_diagnosa', function (Blueprint $table) {
            $table->id('id_form_diagnosa');
            $table->unsignedBigInteger('id_diagnosa');
            $table->unsignedBigInteger('id_gejala');
            $table->unsignedBigInteger('id_detail_penyebab');
            $table->unsignedBigInteger('id_faktor_resiko');
            $table->string('catatan_diagnosa', 255);
            $table->timestamps();
        });

        Schema::table('form_diagnosa', function (Blueprint $table) {
            $table->foreign('id_diagnosa')->references('id_diagnosa')->on('diagnosa');
            $table->foreign('id_gejala')->references('id_gejala')->on('gejala');
            $table->foreign('id_detail_penyebab')->references('id_detail_penyebab')->on('detail_penyebab');
            $table->foreign('id_faktor_resiko')->references('id_faktor_resiko')->on('faktor_resiko');
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_diagnosa');
    }
};
