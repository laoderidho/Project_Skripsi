<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian_kriteria', function (Blueprint $table) {
            $table->id('id_penilaian_kriteria');
            $table->unsignedBigInteger('id_kriteria_luaran');
            $table->string('nilai', 255);
            $table->timestamps();
        });

        // Schema::table('penilaian_kriteria', function (Blueprint $table) {
        //     $table->foreign('id_kriteria_luaran')->references('id_kriteria_luaran')->on('kriteria_luaran');
        // });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_kriteria');
    }
};
