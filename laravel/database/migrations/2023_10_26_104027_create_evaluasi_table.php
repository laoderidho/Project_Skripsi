<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id('id_evaluasi');
            $table->unsignedBigInteger('id_luaran');
            $table->unsignedBigInteger('id_kriteria_luaran');
            $table->string('subjektif', 255);
            $table->string('objektif', 255);
            $table->string('pencapaian', 255);
            $table->string('perencanaan', 255);
            $table->string('catatan_evaluasi', 255);
            $table->timestamps();
        });

        // Schema::table('evaluasi', function (Blueprint $table) {
        //     $table->foreign('id_luaran')->references('id_luaran')->on('luaran');
        //     $table->foreign('id_kriteria_luaran')->references('id_kriteria_luaran')->on('kriteria_luaran');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('evaluasi');
    }
};
